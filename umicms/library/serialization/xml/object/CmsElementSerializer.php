<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
        CmsHierarchicObject::FIELD_LOCKED,
        CmsHierarchicObject::FIELD_DISPLAY_NAME,
        CmsHierarchicObject::FIELD_SLUG,
        CmsHierarchicObject::FIELD_MPATH,
        IActiveAccessibleObject::FIELD_ACTIVE,
    ];

}
