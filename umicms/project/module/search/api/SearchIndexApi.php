<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace umicms\project\module\search\api;

use umi\config\entity\IConfig;
use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\event\IEventObservant;
use umi\event\TEventObservant;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\object\IObject;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umi\spl\config\TConfigSupport;
use umi\stemming\IStemmingAware;
use umi\stemming\TStemmingAware;
use umicms\project\module\search\api\object\SearchIndex;

/**
 * Публичный интерфейс для индексирования модулей CMS для поиска.
 */
class SearchIndexApi extends BaseSearchApi implements IStemmingAware, IConfigIOAware,
    IObjectPersisterAware, IEventObservant
{
    use TStemmingAware;
    use TConfigIOAware;
    use TObjectPersisterAware;
    use TConfigSupport;
    use TEventObservant;

    /**
     * Конфигурация доступных для индексирования коллекций и их полей.
     * @var array|IConfig $collectionsMap
     */
    public $collectionsMap = [];

    /**
     * Перестраивает хранимый поисковый индекс для отдельной коллекции.
     * @param string $collectionName
     */
    public function buildIndex($collectionName)
    {
        $this->fireEvent('search.beforeIndex', ['collectionName' => $collectionName]);

        $indexCollection = $this->getCollectionManager()
            ->getCollection('searchIndex');
        $deleter = $indexCollection
            ->select()
            ->fields([SearchIndex::FIELD_IDENTIFY, SearchIndex::FIELD_REF_GUID])
            ->where(SearchIndex::FIELD_COLLECTION_NAME)
            ->equals($collectionName);
        /** @var $record IObject */
        foreach ($deleter as $record) {
            $indexCollection->delete($record);
        }

        $config = $this->getConfigForCollection($collectionName);
        $collection = $this->getCollectionManager()
            ->getCollection($collectionName);
        $collectionRecords = $collection->select()
            ->fields($config['properties'])
            ->getResult();

        /** @var $record IObject */
        foreach ($collectionRecords as $record) {
            $newIndexRecord = $indexCollection->add();
            $newIndexRecord
                ->setValue(SearchIndex::FIELD_COLLECTION_NAME, $collectionName)
                ->setValue(SearchIndex::FIELD_REF_GUID, $record->getGUID())
                ->setValue(
                    SearchIndex::FIELD_CONTENT,
                    $this->normalizeIndexString($this->extractSearchableContent($record))
                );
            $newIndexRecord->setValue(SearchIndex::FIELD_DATE_INDEXED, new \DateTime());
        }
    }

    /**
     * Переиндексирует отдельный набор объектов.
     * @param IObject[] $objectList
     */
    public function buildIndexForObjects($objectList)
    {
        $this->fireEvent('search.beforeIndex', ['objectList' => $objectList]);

        $indexCollection = $this->getCollectionManager()
            ->getCollection('searchIndex');
        $guidList = [];
        foreach ($objectList as $obj) {
            $guidList[] = $obj->getGUID();
        }

        /** @var $indexRecords IObject[] */
        $indexRecords = $indexCollection->select()
            ->where('targetGuid')
            ->in($guidList)
            ->getResult();

        foreach ($indexRecords as $indexRecord) {
            foreach ($objectList as $obj) {
                if ($obj->getGUID() == $indexRecord->getValue('targetGuid')) {
                    $indexRecord->setValue(
                        'contents',
                        $this->normalizeIndexString($this->extractSearchableContent($obj))
                    );
                }
            }
        }
    }

    /**
     * Возвращает конфигурацию индексирования именованной коллекции.
     * @param string $collectionName Имя коллекции
     * @throws \UnexpectedValueException
     * @return array
     */
    protected function getConfigForCollection($collectionName)
    {
        $config = $this->getIndexableCollectionsConfig();
        if (!isset($config[$collectionName])) {
            throw new \UnexpectedValueException("Collection $collectionName is not mapped for search index");
        }
        $fields = $config[$collectionName];
        return $fields;
    }

    /**
     * Извлекает из объекта текстовые данные, пригодные для помещения в поисковый индекс.
     * @param IObject $object
     * @return string
     */
    public function extractSearchableContent($object)
    {
        $content = '';
        $propertyNames = $this->getConfigForCollection($object->getCollectionName())['properties'];
        //todo remove duplicates
        foreach ($propertyNames as $propName) {
            $val = $object->getValue($propName);
            if (!is_string($val)) {
                continue;
            }
            $content .= " " . $this->filterSearchableText($val);
        }
        return trim($content);
    }

    /**
     * Возвращает список имен коллекций, которые участвуют в индексации и поиске
     * @return array
     */
    public function getIndexableCollectionsNamesList()
    {
        return array_keys($this->getIndexableCollectionsConfig());
    }

    /**
     * Возвращает конфигурацию индексируемых коллекций
     * @return array
     */
    protected function getIndexableCollectionsConfig()
    {
        return $this->configToArray($this->collectionsMap);
    }

    /**
     * Очищает текст от разметки и прочей нетекстовой информации.
     * В отличие от {@see normalizeIndexString()}, не удаляет пунктуацию и не меняет регистр символов,
     * полученный текст можно использовать для цитирования, например, в результатах поиска.
     *
     * @param string $textRaw
     * @return string
     */
    public function filterSearchableText($textRaw)
    {
        $nobr = preg_replace('#<br\s*/?>#uim', ' ', $textRaw);
        return html_entity_decode(strip_tags($nobr));
    }
}
