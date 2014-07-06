<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\metadata\field\relation;

use umi\orm\exception\InvalidArgumentException;
use umi\orm\metadata\field\relation\ObjectRelationField;
use umicms\orm\object\ICmsObject;

/**
 * Поле, для хранения ссылки на объект произвольной коллекции.
 */
class CmsObjectRelationField extends ObjectRelationField
{
    /**
     * Тип поля
     */
    const TYPE = 'cmsObjectRelation';

    /**
     * {@inheritdoc}
     */
    public function validateInputPropertyValue($propertyValue)
    {
        if (!$propertyValue instanceof ICmsObject) {
            throw new InvalidArgumentException($this->translate(
                'Value must be instance of ICmsObject.'
            ));
        }

        return true;
    }
}
 