<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml\view;

use umi\form\EntityAttributesView;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор атрибутов.
 */
class EntityAttributesViewSerializer extends BaseSerializer
{
    /**
     * Сериализует атрибуты в XML.
     * @param EntityAttributesView $view
     * @param array $options
     */
    public function __invoke(EntityAttributesView $view, array $options = [])
    {

        foreach ($view as $key => $value) {
            $this->getXmlWriter()->writeAttribute($key, $value);
        }

    }
}
 