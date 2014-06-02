<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\model\field;

use umicms\exception\UnexpectedValueException;

/**
 * Класс поля связи "один-ко-многим".
 */
class HasManyRelationField extends BaseField
{
    /**
     * Возвращает имя коллекции на которую выставлена связь
     * @throws UnexpectedValueException при невалидной конфигурации
     * @return string
     */
    public function getTargetCollectionName()
    {
        $targetCollectionName = $this->metadata->get('target');
        if (!is_string($targetCollectionName)) {
            throw new UnexpectedValueException($this->translate(
                'Relation field "{name}" configuration should contain target collection name and name should be a string.',
                ['name' => $this->getName()]
            ));
        }

        return $targetCollectionName;
    }

    /**
     * устанавливает имя коллекции, на которую выставлена связь
     * @param string $targetCollectionName
     * @return $this
     */
    public function setTargetCollectionName($targetCollectionName)
    {
        $this->metadata->set('target', $targetCollectionName);

        return $this;
    }

    /**
     * Возвращает имя поля для связи с target-коллекцией
     * @throws UnexpectedValueException при невалидной конфигурации
     * @return string
     */
    public function getTargetFieldName()
    {
        $targetFieldName = $this->metadata->get('targetField');

        if (!is_string($targetFieldName)) {
            throw new UnexpectedValueException($this->translate(
                'Relation field "{name}" configuration should contain target field name and name should be a string.',
                ['name' => $this->getName()]
            ));
        }

        return $targetFieldName;
    }

    /**
     * Устанавливает имя поля для связи с target-коллекцией
     * @param string $targetFieldName
     * @return $this
     */
    public function setTargetFieldName($targetFieldName)
    {
        $this->metadata->set('targetField', $targetFieldName);

        return $this;
    }
}
 