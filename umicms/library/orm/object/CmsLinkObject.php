<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\object;

use umi\orm\metadata\field\relation\BelongsToRelationField;

/**
 * Класс простого объекта UMI.CMS для связи многие-ко-многим.
 */
class CmsLinkObject extends CmsObject
{
    /**
     * Разделитель для автоматически генерируемого отображаемого имени.
     */
    const DISPLAY_NAME_SEPARATOR = ':';

    /**
     * Генерирует отображаемое имя, если оно не было установлено.
     * @return bool
     */
    public function validateDisplayName()
    {
        if (!$this->displayName) {
            $this->displayName = $this->generateDisplayName();
        }

        return true;
    }

    /**
     * Генерирует отображаемое имя, используя имена связанных объектов.
     * @return string
     */
    protected function generateDisplayName()
    {
        $result = [];
        foreach ($this->getAllProperties() as $property) {
            if ($property->getField() instanceof BelongsToRelationField) {

                $object = $property->getValue();
                if ($object instanceof CmsObject && $object->displayName) {
                    $result[] = $object->displayName;
                }
            }
        }
        return implode(self::DISPLAY_NAME_SEPARATOR, $result);
    }
}
 