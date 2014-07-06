<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\dump;

use umi\orm\metadata\field\IField;
use umi\orm\metadata\field\IRelationField;
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;

/**
 * Класс для экспорта объектов в дамп.
 */
class CmsObjectExporter implements ICmsObjectExporter
{
    /**
     * @var array $ignoreFieldTypes список игнорируемых в экспорте типов полей
     */
    public $ignoreFieldTypes = [
        IField::TYPE_VERSION => [],
        IField::TYPE_IDENTIFY => [],
        IField::TYPE_HAS_MANY => [],
        IField::TYPE_HAS_ONE => [],
        IField::TYPE_MANY_TO_MANY => [],
        IField::TYPE_MPATH => [],
        IField::TYPE_URI => [],
        IField::TYPE_COUNTER => []
    ];

    /**
     * Возвращает дамп объектов, выбранных селектором.
     * @param CmsSelector $selector
     * @return array
     */
    public function getDump(CmsSelector $selector)
    {
        $data = [];

        foreach ($selector as $object)
        {
            $data[] = $this->getObjectDump($object);
        }


        return $data;
    }

    /**
     * Возвращает дамп всех данных объекта, сохраненных в БД.
     * @param ICmsObject $object
     * @param bool $withBelongsToRelations включать ли короткую информацию о BelongsTo связях.
     * @return array
     */
    public function getObjectDump(ICmsObject $object, $withBelongsToRelations = true)
    {
        $data = [];

        foreach ($object->getAllProperties() as $fullName => $property)
        {
            $field = $property->getField();

            if (isset($this->ignoreFieldTypes[$field->getType()])) {
                continue;
            }

            if ($field instanceof IRelationField) {
                if ($withBelongsToRelations && $field instanceof BelongsToRelationField) {
                    $relatedObject = $property->getPersistedValue();
                    if ($relatedObject instanceof ICmsObject) {
                        $data[$fullName] = $this->getObjectDump($relatedObject, false);
                    }
                }
            } else {
                $value = $property->getValue();

                if (is_array($value) || is_object($value)) {
                    $data[$fullName] = serialize($value);
                } else {
                    $data[$fullName] = $value;
                }
            }
        }

        return $data;
    }

}
 