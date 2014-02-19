<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umicms\api\BaseHierarchicCollectionApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\news\object\NewsRubric;

/**
 * API для работы с категориями новостей
 */
class NewsRubricApi extends BaseHierarchicCollectionApi
{
    /**
     * @var string $collectionName имя коллекции для хранения категорий новостей
     */
    public $collectionName = 'news_category';

    /**
     * Возвращает новостую рубрику по ее slug
     * @param string $slug
     * @throws NonexistentEntityException если рубрика не существует
     * @return NewsRubric
     */
    public function getRubricBySlug($slug)
    {
        $category = $this->getElementBySlug($slug);

        if (!$category instanceof NewsRubric) {
            throw new NonexistentEntityException(
                $this->translate(
                    'News rubric "{slug}" does not exist',
                    ['slug' => $slug]
                )
            );
        }

        return $category;
    }
}
 