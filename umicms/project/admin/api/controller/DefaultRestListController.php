<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\api\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;
use umi\orm\metadata\IObjectType;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umi\orm\selector\condition\IFieldCondition;
use umicms\exception\OutOfBoundsException;
use umicms\exception\RuntimeException;
use umicms\exception\UnexpectedValueException;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;

/**
 * Контроллер действий над списком.
 */
class DefaultRestListController extends BaseDefaultRestController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {

                $list = $this->applySelectorConditions($this->getList());
                $result = ['collection' => $list];

                if ($list->getLimit()) {
                    $result['meta'] = [
                        'limit' => $list->getLimit(),
                        'offset' => $list->getOffset(),
                        'total' => $list->getTotal()
                    ];
                }

                return $this->createViewResponse(
                    'list',
                    $result
                );
            }
            case 'PUT':
            case 'POST': {
                $object = $this->create($this->getCollectionIncomingData());
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
     * Возвращает список объектов коллекции, с которой работает компонент.
     * @return CmsSelector
     */
    protected function getList() 
    {
        return  $this->getComponent()->getCollection()->select();
    }

    /**
     * Создает и возвращает объект списка.
     * @param array $data данные
     * @throws RuntimeException если невозможно создать объект
     * @return ICmsObject
     */
    protected function create(array $data)
    {
        /**
         * @var SimpleHierarchicCollection|SimpleCollection $collection
         */
        $collection = $this->getCollection();

        switch(true) {
            case $collection instanceof SimpleHierarchicCollection: {
                $object = $this->createHierarchicObject($collection, $data);
                break;
            }
            case $collection instanceof SimpleCollection: {
                $object = $this->createObject($collection, $data);
                break;
            }

            default: {
                throw new RuntimeException(
                    $this->translate(
                        'Cannot create object in collection "{collection}". Unknown collection type.',
                        ['collection' => $this->getCollectionName()]
                    )
                );
            }
        }

        return $this->save($object, $data);
    }

    /**
     * Применяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {

        $selector->limit((int) $this->getQueryVar('limit'), (int) $this->getQueryVar('offset'));

        if ($fields = $this->getQueryVar('fields')) {
            $this->applySelectorFieldFilter($selector, $fields);
        }

        if (is_array($this->getQueryVar('with'))) {
            $this->applySelectorWith($selector, $this->getQueryVar('with'));
        }

        if (is_array($this->getQueryVar('orderBy'))) {
            foreach($this->getQueryVar('orderBy') as $fieldPath => $direction) {
                $fieldPath = $this->normalizeFieldPath($fieldPath);
                $selector->orderBy($fieldPath, strtoupper($direction));
            }
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
     * @param CmsSelector $selector
     * @param array $with
     * @return CmsSelector
     */
    protected function applySelectorWith(CmsSelector $selector, array $with)
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
     * @param CmsSelector $selector
     * @param string $fields
     * @return CmsSelector
     */
    protected function applySelectorFieldFilter(CmsSelector $selector, $fields)
    {
        $fieldNames = explode(',', $fields);

        return $selector->fields($fieldNames);
    }

    /**
     * Применяет фильтр "равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
     */
    protected function applyEqualsFilter(IFieldCondition $condition, $value)
    {
        return $condition->in(explode(',', $value));
    }

    /**
     * Применяет фильтр "не равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
     */
    protected function applyNotEqualsFilter(IFieldCondition $condition, $value)
    {
        return $condition->notIn(explode(',', $value));
    }

    /**
     * Применяет фильтр "больше".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
     */
    protected function applyMoreFilter(IFieldCondition $condition, $value)
    {
        return $condition->more($value);
    }

    /**
     * Применяет фильтр "больше или равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
     */
    protected function applyEqualsOrMoreFilter(IFieldCondition $condition, $value)
    {
        return $condition->equalsOrMore($value);
    }

    /**
     * Применяет фильтр "меньше".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
     */
    protected function applyLessFilter(IFieldCondition $condition, $value)
    {
        return $condition->less($value);
    }

    /**
     * Применяет фильтр "меньше или равно".
     * @param IFieldCondition $condition условие для поля фильтра
     * @param string $value значения фильтра
     * @return CmsSelector
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
     * @return CmsSelector
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
     * @return CmsSelector
     */
    protected function applyLikeFilter(IFieldCondition $condition, $value)
    {
        return $condition->like($value);
    }

    /**
     * Применяет фильтр поля на список.
     * @param CmsSelector $selector список
     * @param string $name путь поля
     * @param string $value информация о фильтре
     * @throws OutOfBoundsException если не удалось определить тип фильтра
     * @return CmsSelector
     */
    protected function applySelectorConditionFilter(CmsSelector $selector, $name, $value)
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
        return str_replace('_', CmsSelector::FIELD_SEPARATOR, $path);
    }

    /**
     * Создает объект в коллекции.
     * @param SimpleHierarchicCollection $collection коллекция
     * @param array $data данные объекта
     * @throws RuntimeException в случае недостаточности данных для создания объекта
     * @return CmsHierarchicObject
     */
    private function createHierarchicObject(SimpleHierarchicCollection $collection, array $data)
    {
        if (!isset($data['slug'])) {
            throw new RuntimeException(
                $this->translate('Cannot create hierarchic object. Option "{option}" required.',
                    ['option' => 'slug']
                )
            );
        }

        $typeName = isset($data['type']) ? $data['type'] : IObjectType::BASE;
        $parent = null;
        if (isset($data['parent'])) {
            /**
             * @var CmsHierarchicObject $parent
             */
            $parent = $collection->getById($data['parent']);
        }

        return $collection->add($data['slug'], $typeName, $parent);

    }

    /**
     * Создает объект в коллекции.
     * @param SimpleCollection $collection коллекция
     * @param array $data данные объекта
     * @return ICmsObject
     */
    private function createObject(SimpleCollection $collection, array $data)
    {
        $typeName = isset($data['type']) ? $data['type'] : IObjectType::BASE;

        return $collection->add($typeName);
    }

}
 