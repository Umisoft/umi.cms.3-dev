<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml\object;

use umicms\object\CmsElement;
use umicms\serialization\xml\BaseSerializer;

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
        CmsElement::FIELD_IDENTIFY,
        CmsElement::FIELD_GUID,
        CmsElement::FIELD_VERSION,
        CmsElement::FIELD_ACTIVE,
        CmsElement::FIELD_LOCKED,
        CmsElement::FIELD_DISPLAY_NAME,
        CmsElement::FIELD_SLUG,
        CmsElement::FIELD_MPATH
    ];

}
