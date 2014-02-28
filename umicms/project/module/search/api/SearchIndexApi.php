<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\search\api;

use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\object\IObject;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umi\stemming\IStemmingAware;
use umi\stemming\TStemmingAware;
use umicms\api\IPublicApi;

/**
 * Class SearchIndexApi
 */
class SearchIndexApi extends BaseSearchApi implements IPublicApi, IStemmingAware, IConfigIOAware, IObjectPersisterAware
{
    use TStemmingAware;
    use TConfigIOAware;
    use TObjectPersisterAware;

    /**
     * Перестраивает хранимый поисковый индекс
     * @param $collectionId
     */
    public function buildIndex($collectionId)
    {
        $indexCollection = $this->getCollectionManager()
            ->getCollection('searchIndex');
        $deleter = $indexCollection
            ->select()
            ->fields(IObject::FIELD_IDENTIFY)
            ->where('collectionId')
            ->equals($collectionId);
        /** @var $record IObject */
        foreach ($deleter as $record) {
            $indexCollection->delete($record);
        }

        $config = $this->getConfigForCollection($collectionId);
        $collection = $this->getCollectionManager()
            ->getCollection($collectionId);
        $collectionRecords = $collection->select()
            ->fields($config['properties'])
            ->getResult();

        /** @var $record IObject */
        foreach ($collectionRecords as $record) {
            $indexCollection->add()
                ->setValue('collectionId', $collectionId)
                ->setValue('content', $this->normalizeIndexString($this->extractSearchableContent($record)));
        }
        $this->getObjectPersister()
            ->commit();
    }

    /**
     * @param IObject[] $objectList
     */
    public function buildIndexForObjects($objectList)
    {
        $indexCollection = $this->getCollectionManager()
            ->getCollection('searchIndex');
        $guidList = [];
        foreach ($objectList as $obj) {
            $guidList[] = $obj->getGUID();
        }
        $collectionId = current($objectList)
            ->getCollection()
            ->getName();
        $config = $this->getConfigForCollection($collectionId);

        /** @var $indexRecords IObject[] */
        $indexRecords = $indexCollection->select()
            ->where('targetGuid')
            ->in($guidList)
            ->getResult();
        foreach ($indexRecords as $indexRecord) {
            foreach ($objectList as $obj) {
                if ($obj->getGUID() == $indexRecord->getValue('targetGuid')) {
                    $indexRecord->setValue(
                        'content',
                        $this->normalizeIndexString($this->extractSearchableContent($obj))
                    );
                }
            }
        }
        $this->getObjectPersister()
            ->commit();
    }

    /**
     * @param $collectionId
     * @return mixed|\umi\config\entity\IConfig
     */
    protected function getConfigForCollection($collectionId)
    {
        $config = $this->readConfig('~search')
            ->get($collectionId);
        return $config;
    }
}
