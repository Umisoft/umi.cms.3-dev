<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api;

use umi\orm\selector\condition\IFieldConditionGroup;
use umi\orm\selector\ISelector;
use umi\rss\IRssFeed;
use umi\rss\IRssFeedAware;
use umi\rss\TRssFeedAware;
use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\blog\api\object\BlogTag;
use umicms\project\module\blog\api\object\RssImportPost;

/**
 * Публичное API модуля "Блоги"
 */
class BlogApi extends BaseComplexApi implements IPublicApi, IUrlManagerAware, IRssFeedAware
{
    use TUrlManagerAware;
    use TRssFeedAware;

    /**
     * Возвращает репозиторий для работы с постами.
     * @return BlogPostRepository
     */
    public function post()
    {
        return $this->getApi('umicms\project\module\blog\api\BlogPostRepository');
    }

    /**
     * Возвращает репозиторий для работы с авторами.
     * @return BlogAuthorRepository
     */
    public function author()
    {
        return $this->getApi('umicms\project\module\blog\api\BlogAuthorRepository');
    }

    /**
     * Возвращает репозиторий для работы с категориями блога.
     * @return BlogCategoryRepository
     */
    public function category()
    {
        return $this->getApi('umicms\project\module\blog\api\BlogCategoryRepository');
    }

    /**
     * Возвращает репозиторий для работы с тэгами блога.
     * @return BlogTagRepository
     */
    public function tag()
    {
        return $this->getApi('umicms\project\module\blog\api\BlogTagRepository');
    }

    /**
     * Возвращает репозиторий для работы с импортируемыми RSS-лентами.
     * @return RssImportPostRepository
     */
    public function rss()
    {
        return $this->getApi('umicms\project\module\blog\api\RssImportPostRepository');
    }

    /**
     * Возвращает селектор для выборки постов.
     * @param int $limit максимальное количество постов
     * @return ISelector
     */
    public function getPosts($limit = null)
    {
        $posts = $this->post()->select()
            ->orderBy(BlogPost::FIELD_PUBLISH_TIME, ISelector::ORDER_DESC);

        if ($limit) {
            $posts->limit($limit);
        }

        return $posts;
    }

    /**
     * Строит RSS-ленту.
     * @param string $title заголовок RSS-ленты
     * @param string $description описание RSS-ленты
     * @param ISelector|BlogPost[] $postSelector список постов
     * @return IRssFeed
     */
    public function getPostRssFeed($title, $description, ISelector $postSelector)
    {
        $rssFeed = $this->createRssFeed(
            $this->getUrlManager()->getProjectUrl(true),
            $title,
            $description
        );

        foreach ($postSelector as $blogPost) {
            $rssFeed->addItem()
                ->setTitle($blogPost->h1)
                ->setContent($blogPost->announcement)
                ->setUrl($blogPost->getPageUrl(true))
                ->setDate($blogPost->publishTime);
        }

        return $rssFeed;
    }

    /**
     * Возвращает селектор для выборки постов указанных категорий.
     * @param BlogCategory[] $categories список GUID категорий блога
     * @param int $limit максимальное количество постов
     * @return ISelector
     */
    public function getCategoryPost(array $categories = [], $limit = null)
    {
        $posts = $this->getPosts($limit);

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($categories as $category) {
            $posts->where(BlogPost::FIELD_CATEGORY)->equals($category);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выборки постов указанных тэгов.
     * @param BlogTag[] $tags список GUID тэгов постов
     * @param int $limit максимальное количество постов
     * @return ISelector
     */
    public function getTagPost(array $tags = [], $limit = null)
    {
        $posts = $this->getPosts($limit);

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($tags as $tag) {
            $posts->where(BlogPost::FIELD_TAGS)->equals($tag);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выборки постов в указанной категории.
     * @param BlogCategory|null $parentCategory GUID категории
     * @param int $limit максимальное количество категорий
     * @return ISelector
     */
    public function getCategories(BlogCategory $parentCategory = null, $limit = null)
    {
        $categories = $this->category()->selectChildren($parentCategory);

        if ($limit) {
            $categories->limit($limit);
        }

        return $categories;
    }

    /**
     * Возвращает селектор для выборки тэгов.
     * @param int $limit максимальное количество тэгов
     * @return ISelector
     */
    public function getTags($limit = null)
    {
        $tags = $this->tag()->select();

        if ($limit) {
            $tags->limit($limit);
        }

        return $tags;
    }

    /**
     * Выполнение импорта постов из RSS-ленты.
     * @param RssImportPost $rssImportPost импортируемая RSS-лента
     */
    public function importRss(RssImportPost $rssImportPost)
    {
        $this->rss()->importRss($rssImportPost, $this->post());
    }

}
