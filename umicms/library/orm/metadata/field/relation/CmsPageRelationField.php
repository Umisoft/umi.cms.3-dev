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
use umicms\orm\object\ICmsPage;

/**
 * Поле, для хранения ссылки на страницу произвольной коллекции.
 */
class CmsPageRelationField extends ObjectRelationField
{
    /**
     * Тип поля
     */
    const TYPE = 'cmsPageRelation';

    /**
     * {@inheritdoc}
     */
    public function validateInputPropertyValue($propertyValue)
    {
        if (!$propertyValue instanceof ICmsPage) {
            throw new InvalidArgumentException($this->translate(
                'Value must be instance of ICmsPage.'
            ));
        }

        return true;
    }
}
 