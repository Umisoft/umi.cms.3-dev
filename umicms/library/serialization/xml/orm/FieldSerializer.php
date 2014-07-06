<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\xml\orm;


use umi\orm\metadata\field\BaseField;
use umicms\serialization\xml\BaseSerializer;

/**
 * XML-сериализатор для свойств объектов.
 */
class FieldSerializer extends BaseSerializer
{
    /**
     * Сериализует поле в XML.
     * @param BaseField $field
     * @param array $options опции сериализации
     */
    public function __invoke(BaseField $field, array $options = [])
    {
        $this->writeElement('field', [
            'type' => $field->getType()
        ]);
    }
}
