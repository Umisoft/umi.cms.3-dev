<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\selector;

use umi\orm\selector\condition\IFieldCondition;
use umicms\exception\OutOfBoundsException;
use umicms\exception\UnexpectedValueException;

/**
 * Трейт для настройки селектора
 */
trait TSelectorConfigurator
{
    /**
     * @see TLocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * Применяет сортировку.
     * @param CmsSelector $selector
     * @param array $orderBy
     * @return CmsSelector
     */
    protected function applySelectorOrderBy(CmsSelector $selector, array $orderBy)
    {
        foreach($orderBy as $fieldPath => $direction) {
            $fieldPath = $this->normalizeFieldPath($fieldPath);
            $selector->orderBy($fieldPath, strtoupper($direction));
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
     * Указывает поля для выборки.
     * @param CmsSelector $selector
     * @param string $fields
     * @return CmsSelector
     */
    protected function applySelectorSelectedFields(CmsSelector $selector, $fields)
    {
        $fieldNames = explode(',', $fields);

        return $selector->fields($fieldNames);
    }

    /**
     * Применяет фильтрацию по полям.
     * @param CmsSelector $selector
     * @param array $filters
     * @return CmsSelector
     */
    protected function applySelectorConditionFilters(CmsSelector $selector, array $filters)
    {
        foreach($filters as $name => $value) {
            $name = $this->normalizeFieldPath($name);
            $value = (array) $value;

            foreach ($value as $val) {
                $this->applySelectorConditionFilter($selector, $name, $val);
            }
        }

        return $selector;
    }

    /**
     * Применяет фильтр поля.
     * @param CmsSelector $selector
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
     * Нормализует путь поля для условий выборки.
     * @param string $path
     * @return string
     */
    private function normalizeFieldPath($path)
    {
        return str_replace('_', CmsSelector::FIELD_SEPARATOR, $path);
    }
}
 