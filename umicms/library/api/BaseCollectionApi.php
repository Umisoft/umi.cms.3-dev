<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\ICommonHierarchy;
use umi\orm\collection\IHierarchicCollection;
use umi\orm\collection\ILinkedHierarchicCollection;
use umi\orm\collection\ISimpleCollection;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\selector\ISelector;
use umicms\base\object\ICmsObject;

/**
 * Базовый класс API для работы с ORM-коллекцией.
 */
abstract class BaseCollectionApi implements ICollectionManagerAware, ILocalizable
{
    use TCollectionManagerAware;
    use TLocalizable;

    /**
     * @var string $collectionName имя коллекции
     */
    public $collectionName;

    /**
     * Возвращает селектор для выбора элементов коллекции.
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function select($onlyActive = true)
    {
        $select = $this
            ->getCollection()
            ->select();
        if ($onlyActive) {
            $select
                ->where(ICmsObject::FIELD_ACTIVE)
                ->equals(true);
        }

        return $select;
    }

    /**
     * Возвращает коллекцию.
     * @return ICommonHierarchy|IHierarchicCollection|ILinkedHierarchicCollection|ISimpleCollection
     */
    protected function getCollection()
    {
        return $this->getCollectionManager()->getCollection($this->collectionName);
    }
}
