<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\news\api;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\SimpleHierarchicCollection;
use umi\orm\collection\TCollectionManagerAware;
use umicms\exception\NonexistentEntityException;
use umicms\module\news\model\Category;

/**
 * API для работы с категориями новостей
 */
class CategoryApi implements ICollectionManagerAware, ILocalizable
{
    use TCollectionManagerAware;
    use TLocalizable;

    /**
     * @var string $collectionName имя коллекции для хранения категорий новостей
     */
    public $collectionName = 'news_category';

    /**
     * Возвращает категорию новостей по ее slug
     * @param string $slug
     * @throws NonexistentEntityException если категории не существует
     * @return Category
     */
    public function getCategoryBySlug($slug)
    {
        $category = $this->getCategoryCollection()
            ->select()
            ->where('slug')->equals($slug)
            ->limit(1)
            ->result()
            ->fetch();

        if (!$category instanceof Category) {
            throw new NonexistentEntityException(
                $this->translate(
                    'News category does not exist'
                )
            );
        }

        return $category;
    }

    /**
     * @return SimpleHierarchicCollection
     */
    protected function getCategoryCollection()
    {
        return $this->getCollectionManager()->getCollection($this->collectionName);
    }
}
 