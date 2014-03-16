<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json\form;

use umi\form\element\BaseFormElement;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для элемента формы.
 */
class BaseFormElementSerializer extends BaseSerializer
{
    /**
     * Сериализует элемент формы в JSON.
     * @param BaseFormElement $element
     * @param array $options опции сериализации
     */
    public function __invoke(BaseFormElement $element, array $options = [])
    {

        $result = [
            'type' => $element::TYPE_NAME,
            'name' => $element->getName()
        ];

        if ($attributes = $element->getAttributes()) {
            $result['attributes'] = $attributes;
        }

        if ($dataSource = $element->getDataSource()) {
            $result['dataSource'] = $dataSource;
        }

        $this->delegate($result);
    }
}
 