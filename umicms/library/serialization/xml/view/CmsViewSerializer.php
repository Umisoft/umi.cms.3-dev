<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\xml\view;

use umicms\hmvc\view\CmsView;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор view.
 */
class CmsViewSerializer extends BaseSerializer
{
    /**
     * Сериализует view в XML.
     * @param CmsView $view
     * @param array $options опции сериализации
     */
    public function __invoke(CmsView $view, array $options = [])
    {
        $attributes = [];
        $properties = [];

        $viewAttributes = $view->getXmlAttributes();
        $viewExcludes = $view->getXmlExcludes();

        foreach ($view as $name => $value) {
            if (in_array($name, $viewExcludes)) {
                continue;
            }

            if (in_array($name, $viewAttributes)) {
                $attributes[$name] = $value;
            } else {
                $properties[$name] = $value;
            }
        }

        foreach ($attributes as $name => $attribute) {
            $this->writeAttribute($name, $attribute);
        }

        $this->delegate($properties, $options);
    }
}
 