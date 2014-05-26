<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\model\field;

use umicms\exception\UnexpectedValueException;

/**
 * Класс поля связи "многие-ко-многим".
 */
class ManyToManyRelationField extends BaseField
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

    /**
     * Возвращает имя коллекции, которая является мостом для связи c target-коллекцией
     * @throws UnexpectedValueException при невалидной конфигурации
     * @return string
     */
    public function getBridgeCollectionName()
    {
        $bridgeCollectionName = $this->metadata->get('bridge');
        if (!is_string($bridgeCollectionName)) {
            throw new UnexpectedValueException($this->translate(
                'Relation field "{name}" configuration should contain bridge collection name and name should be a string.',
                ['name' => $this->getName()]
            ));
        }

        return $bridgeCollectionName;
    }

    /**
     * Устанавливает имя коллекции, которая является мостом для связи c target-коллекцией
     * @param string $bridgeCollectionName
     * @return $this
     */
    public function setBridgeCollectionName($bridgeCollectionName)
    {
        $this->metadata->set('bridge', $bridgeCollectionName);

        return $this;
    }

    /**
     * Возвращает имя связанного поля в bridge-коллекции
     * @throws UnexpectedValueException при невалидной конфигурации
     * @return string
     */
    public function getRelatedFieldName()
    {
        $relatedFieldName = $this->metadata->get('relatedField');

        if (!is_string($relatedFieldName)) {
            throw new UnexpectedValueException($this->translate(
                'Relation field "{name}" configuration should contain related field name and name should be a string.',
                ['name' => $this->getName()]
            ));
        }

        return $relatedFieldName;
    }

    /**
     * Устанавливает имя связанного поля в bridge-коллекции
     * @param string $relatedFieldName
     * @return $this
     */
    public function setRelatedFieldName($relatedFieldName)
    {
        $this->metadata->set('relatedField', $relatedFieldName);

        return $this;
    }

}
 