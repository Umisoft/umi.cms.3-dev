<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umi\orm\selector\condition\IFieldCondition;
use umi\orm\selector\ISelector;
use umicms\exception\OutOfBoundsException;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\controller\BaseController;
use umicms\orm\object\ICmsObject;

/**
 * Базовый контроллер действий над списком.
 */
abstract class BaseRestListController extends BaseController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

    /**
     * Возвращает список.
     * @return ISelector
     */
    abstract protected function getList();

    /**
     * Создает и возвращает объект списка.
     * @param array $data данные
     * @return ICmsObject
     */
    abstract protected function create(array $data);

    /**
     * Возвращает имя коллекции.
     * @return string
     */
    abstract protected function getCollectionName();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                return $this->createViewResponse(
                    'list',
                    [$this->getCollectionName() => $this->applySelectorConditions($this->getList())]
                );
            }
            case 'PUT':
            case 'POST': {
                $object = $this->create($this->getIncomingData());
                return $this->createViewResponse(
                    'item', [$this->getCollectionName() => $object]
                );
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    'HTTP method is not implemented.',
                    ['GET', 'POST', 'PUT']
                );
            }

            default: {
                throw new HttpException(
                    Response::HTTP_NOT_IMPLEMENTED,
                    'HTTP method is not implemented.'
                );
            }
        }
    }


    /**
     * Возвращает данные для изменения объекта.
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    protected function getIncomingData()
    {
        $inputData = file_get_contents('php://input');
        if (!$inputData) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request body is empty.');
        }

        $data = @json_decode($inputData, true);

        if ($error = json_last_error()) {
            if (function_exists('json_last_error_msg')) {
                $error = json_last_error_msg();
            }
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'JSON parse error: ' . $error);
        }

        if (!isset($data[$this->getCollectionName()]) || !is_array($data[$this->getCollectionName()])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Object data not found.');
        }

        return $data[$this->getCollectionName()];
    }

    /**
     * Применяет условия выборки.
     * @param ISelector $selector
     * @return ISelector
     */
    protected function applySelectorConditions(ISelector $selector)
    {

        $selector->limit($this->getQueryVar('limit'), $this->getQueryVar('offset'));

        if ($fields = $this->getQueryVar('fields')) {
            $this->applySelectorFieldFilter($selector, $fields);
        }

        if (is_array($this->getQueryVar('with'))) {
            $this->applySelectorWith($selector, $this->getQueryVar('with'));
        }

        if (is_array($this->getQueryVar('filters'))) {
            foreach($this->getQueryVar('filters') as $name => $value) {
                $name = $this->normalizeFieldPath($name);
                $value = (array) $value;

                foreach ($value as $val) {
                    $this->applySelectorConditionFilter($selector, $name, $val);
                }
            }
        }

        return $selector;
    }

    /**
     * Применяет фильтр к связанным выбираемым полям.
     * @param ISelector $selector
     * @param array $with
     * @return ISelector
     */
    protected function applySelectorWith(ISelector $selector, array $with)
    {
        foreach ($with as $fieldPath => $fieldList) {
            $fieldPath = $this->normalizeFieldPath($fieldPath);
            $fields = $fieldList ? explode(',', $fieldList) : [];
            $selector->with($fieldPath, $fields);
        }

        return $selector;
    }

    /**
     * Применяет фильтр к выбираемым полям.
     * @param ISelector $selector
     * @param string $fields
     * @return ISelector
     */
    protected function applySelectorFieldFilter(ISelector $selector, $fields)
    {
        $fieldNames = explode(',', $fields);

        return $selector->fields($fieldNames);
    }

    /**
     * Применяет фильтр "равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyEqualsFilter(IFieldCondition $condition, $value)
    {
        return $condition->in(explode(',', $value));
    }

    /**
     * Применяет фильтр "не равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyNotEqualsFilter(IFieldCondition $condition, $value)
    {
        return $condition->notIn(explode(',', $value));
    }

    /**
     * Применяет фильтр "больше".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyMoreFilter(IFieldCondition $condition, $value)
    {
        return $condition->more($value);
    }

    /**
     * Применяет фильтр "больше или равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyEqualsOrMoreFilter(IFieldCondition $condition, $value)
    {
        return $condition->equalsOrMore($value);
    }

    /**
     * Применяет фильтр "меньше".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyLessFilter(IFieldCondition $condition, $value)
    {
        return $condition->less($value);
    }

    /**
     * Применяет фильтр "меньше или равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyEqualsOrLessFilter(IFieldCondition $condition, $value)
    {
        return $condition->equalsOrLess($value);
    }

    /**
     * Применяет фильтр "между".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @throws UnexpectedValueException если невозможно определить границы фильтра
     * @return ISelector
     */
    protected function applyBetweenFilter(IFieldCondition $condition, $value)
    {
        $values = explode(',', $value);

        if (count($values) < 2) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Selection is not possible. For "between"  field "{field}" condition minimal and max values expected',
                    ['field' => $condition->getField()->getName()]
                )
            );
        }

        return $condition->between($values[0], $values[1]);
    }

    /**
     * Применяет фильтр "похоже".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return ISelector
     */
    protected function applyLikeFilter(IFieldCondition $condition, $value)
    {
        return $condition->like($value);
    }

    /**
     * Применяет фильтр поля на список.
     * @param ISelector $selector список
     * @param string $name путь поля
     * @param string $value информация о фильтре
     * @throws OutOfBoundsException если не удалось определить тип фильтра
     * @return ISelector
     */
    protected function applySelectorConditionFilter(ISelector $selector, $name, $value)
    {
        $condition = $selector->where($name);
        if (preg_match('|^(?P<expression>\w+)\((?P<value>.*)\)$|i', $value, $matches)) {
            switch (strtolower($matches['expression'])) {
                case 'notequals':
                    return $this->applyNotEqualsFilter($condition, $matches['value']);
                case 'equals':
                case 'in':
                    return $this->applyEqualsFilter($condition, $matches['value']);
                case 'equalsorless':
                    return $this->applyEqualsOrLessFilter($condition, $matches['value']);
                case 'equalsormore':
                    return $this->applyEqualsOrMoreFilter($condition, $matches['value']);
                case 'more':
                    return $this->applyMoreFilter($condition, $matches['value']);
                case 'less':
                    return $this->applyLessFilter($condition, $matches['value']);
                case 'like':
                    return $this->applyLikeFilter($condition, $matches['value']);
                case 'between':
                    return $this->applyBetweenFilter($condition, $matches['value']);
                case 'null':
                    return $condition->isNull();
                case 'notnull':
                    return $condition->notNull();
                default:
                    throw new OutOfBoundsException(
                        $this->translate(
                            'Selection is not possible. Unknown condition type for field "{field}".',
                            ['field' => $name]
                        )
                    );
            }
        }

        return $condition->equals($value);
    }

    /**
     * Нормализует путь поля для условий выборки.
     * @param string $path
     * @return string
     */
    private function normalizeFieldPath($path)
    {
        return str_replace('_',ISelector::FIELD_SEPARATOR, $path);
    }



}
 