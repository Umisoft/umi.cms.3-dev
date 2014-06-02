<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json\orm;

use umi\orm\metadata\Metadata;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для метаданных коллекции.
 */
class MetadataSerializer extends BaseSerializer
{
    /**
     * Сериализует метаданные в JSON.
     * @param Metadata $metadata
     * @param array $options опции сериализации
     */
    public function __invoke(Metadata $metadata, array $options = [])
    {
        $this->getJsonWriter()->startElement('fields');
        $this->delegate(array_values($metadata->getFields()), $options);
        $this->getJsonWriter()->endElement();
    }
}
 