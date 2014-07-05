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
 