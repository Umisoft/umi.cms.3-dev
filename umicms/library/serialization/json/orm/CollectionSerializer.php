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

use umi\orm\collection\BaseCollection;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\ICmsCollection;
use umicms\serialization\json\BaseSerializer;

/**
 * JSON-сериализатор для коллекции объектов.
 */
class CollectionSerializer extends BaseSerializer implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Сериализует коллекцию в JSON.
     * @param BaseCollection $collection
     * @param array $options опции сериализации
     */
    public function __invoke(BaseCollection $collection, array $options = [])
    {

        if ($collection instanceof ICmsCollection) {
            $options['dictionaries'] = $collection->getDictionaryNames();
        }

        $this->getJsonWriter()->startElement('name');
        $this->writeRaw($collection->getName());
        $this->getJsonWriter()->endElement();

        $this->getJsonWriter()->startElement('type');
        $this->writeRaw($collection->getType());
        $this->getJsonWriter()->endElement();

        if ($collection instanceof ICmsCollection && $collection->hasHandler('admin')) {
            $this->getJsonWriter()->startElement('source');
            $this->writeRaw($this->getUrlManager()->getCollectionResourceUrl($collection));
            $this->getJsonWriter()->endElement();
        }

        $this->delegate($collection->getMetadata(), $options);
    }
}
 