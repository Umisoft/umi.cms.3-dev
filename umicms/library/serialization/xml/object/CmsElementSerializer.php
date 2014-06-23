<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml\object;

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\CmsHierarchicObject;

/**
 * XML-сериализатор для CmsObject.
 */
class CmsElementSerializer extends CmsObjectSerializer
{
    /**
     * Список полей, которые будут выведены как атрибуты элемента.
     * @var array $attributes
     */
    protected $attributes = [
        CmsHierarchicObject::FIELD_IDENTIFY,
        CmsHierarchicObject::FIELD_GUID,
        CmsHierarchicObject::FIELD_VERSION,
        CmsHierarchicObject::FIELD_DISPLAY_NAME,
        CmsHierarchicObject::FIELD_SLUG,
        CmsHierarchicObject::FIELD_MPATH,
        IActiveAccessibleObject::FIELD_ACTIVE
    ];

}
