<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
 