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
 * Класс поля связи "многие-к-одному".
 */
class BelongsToRelationField extends BaseField
{
    /**
     * Возвращает имя коллекции, на которую выставлена связь
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
}
 