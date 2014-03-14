<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\repository;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\orm\collection\CommonHierarchy;
use umicms\orm\collection\LinkedHierarchicCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;

/**
 * Базовый класс API-репозитория объектов UMI.CMS
 */
abstract class BaseObjectRepository implements ICollectionManagerAware, ILocalizable
{
    use TCollectionManagerAware;
    use TLocalizable;

    /**
     * @var string $collectionName имя коллекции
     */
    protected $collectionName;

    /**
     * Возвращает коллекцию с которой связан репозиторий.
     * @return SimpleCollection|SimpleHierarchicCollection|CommonHierarchy|LinkedHierarchicCollection
     */
    public function getCollection()
    {
        return $this->getCollectionManager()->getCollection($this->collectionName);
    }

}
