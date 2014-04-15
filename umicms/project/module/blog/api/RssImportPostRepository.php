<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api;

use Exception;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\rss\IRssFeedAware;
use umi\rss\RssItem;
use umi\rss\TRssFeedAware;
use umicms\api\repository\BaseObjectRepository;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\RssImportPost;

/**
 * Репозиторий для работы с коллекцией импортируемых RSS-лент.
 */
class RssImportPostRepository extends BaseObjectRepository implements IRssFeedAware, ILocalizable
{
    use TRssFeedAware;
    use TLocalizable;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'rssImportPost';

    /**
     * Возвращает селектор для выбора импортируемых RSS-лент.
     * @return CmsSelector|RssImportPost[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает RSS-ленту по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить RSS-ленту
     * @return RssImportPost
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find rss item by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает RSS-ленту по ее id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить RSS-ленту
     * @return RssImportPost
     */
    public function getById($id)
    {
        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find rss item by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет RSS-ленту.
     * @return RssImportPost
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает RSS-ленту на удаление.
     * @param RssImportPost $item
     * @return $this
     */
    public function delete(RssImportPost $item)
    {
        $this->getCollection()->delete($item);

        return $this;
    }

    /**
     * Импортирует пост из RSS-ленты.
     * @param RssImportPost $rssImportPost
     * @param BlogPostRepository $blogPostRepository
     * @throws RuntimeException
     * @internal param string $guid GUID импортируемой RSS-ленты
     * @return $this
     */
    public function importRss(RssImportPost $rssImportPost, BlogPostRepository $blogPostRepository)
    {
        try {
            $xml = \GuzzleHttp\get($rssImportPost->rssUrl)
                ->xml(['object' => false]);
        } catch (Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $rssImportPost->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromSimpleXml($xml);

        $items = $rssFeed->getRssItems();
        foreach ($items as $item) {
            $this->importRssItem($item, $rssImportPost, $blogPostRepository);
        }

        return $this;
    }

    /**
     * Импортирует пост из RSS-ленты.
     * @param RssItem $item
     * @param RssImportPost $rssImportPost
     * @param BlogPostRepository $blogPostRepository
     */
    protected function importRssItem(RssItem $item, RssImportPost $rssImportPost, BlogPostRepository $blogPostRepository)
    {
        try {
            $blogPostRepository->getPostBySource($item->getUrl());
        } catch(NonexistentEntityException $e) {
            $blogPost = $blogPostRepository->add();
            if ($item->getTitle()) {
                $blogPost->displayName = $item->getTitle();
                $blogPost->h1 = $item->getTitle();
            }
            if ($item->getContent()) {
                $blogPost->contents = $item->getContent();
            }
            if ($item->getDate()) {
                $blogPost->publishTime->setTimestamp($item->getDate()->getTimestamp());
                $blogPost->publishTime->setTimezone($item->getDate()->getTimezone());
            }
            if ($item->getUrl()) {
                $blogPost->source = $item->getUrl();
            }
            $blogPost->slug = $blogPost->guid;
            $blogPost->category = $rssImportPost->category;
            foreach ($rssImportPost->tags as $subject) {
                $blogPost->tags->attach($subject);
            }
        }
    }
}
