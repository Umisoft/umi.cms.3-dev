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

use umicms\orm\object\ICmsObject;
use umicms\project\module\search\model\object\SearchIndex;

/**
 * XML-сериализатор для поискового индекса.
 */
class SearchIndexSerializer extends CmsObjectSerializer
{
    /**
     * Сериализует CmsObject в XML.
     * @param ICmsObject $object
     * @param array $options опции сериализации
     */
    public function __invoke(ICmsObject $object, array $options = [])
    {
        /**
         * @var SearchIndex $object
         */
        parent::__invoke($object, $options);

        $this->getXmlWriter()->startElement('indexedObject');
        $this->delegate($object->getIndexedObject());
        $this->getXmlWriter()->endElement();
    }
}
 