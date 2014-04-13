<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

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
use umicms\project\module\news\api\object\RssImportItem;

/**
 * Репозиторий для работы с коллекцией импортируемых RSS-лент.
 */
class RssImportItemRepository extends BaseObjectRepository implements IRssFeedAware, ILocalizable
{
    use TRssFeedAware;
    use TLocalizable;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'rssImportItem';

    /**
     * Возвращает селектор для выбора импортируемых RSS-лент.
     * @return CmsSelector|RssImportItem[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает RSS-ленту по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить RSS-ленту
     * @return RssImportItem
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
     * @return RssImportItem
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
     * @return RssImportItem
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает RSS-ленту на удаление.
     * @param RssImportItem $item
     * @return $this
     */
    public function delete(RssImportItem $item)
    {
        $this->getCollection()->delete($item);

        return $this;
    }

    /**
     * Импортирует новости из RSS-ленты.
     * @param RssImportItem $rssImportItem
     * @param NewsItemRepository $newsItemRepository
     * @throws RuntimeException
     * @internal param string $guid GUID импортируемой RSS-ленты
     * @return $this
     */
    public function importRss(RssImportItem $rssImportItem, NewsItemRepository $newsItemRepository)
    {
        try {
            $xml = \GuzzleHttp\get($rssImportItem->rssUrl)
                ->xml(['object' => false]);
        } catch (Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $rssImportItem->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromSimpleXml($xml);

        $items = $rssFeed->getRssItems();
        foreach ($items as $item) {
            $this->importRssItem($item, $rssImportItem, $newsItemRepository);
        }

        return $this;
    }

    /**
     * Импортирует новость из RSS-ленты.
     * @param RssItem $item
     * @param RssImportItem $rssImportItem
     * @param NewsItemRepository $newsItemRepository
     */
    protected function importRssItem(RssItem $item, RssImportItem $rssImportItem, NewsItemRepository $newsItemRepository)
    {
        try {
            $newsItemRepository->getNewsBySource($item->getUrl());
        } catch(NonexistentEntityException $e) {
            $newsItem = $newsItemRepository->add();
            if ($item->getTitle()) {
                $newsItem->displayName = $item->getTitle();
                $newsItem->h1 = $item->getTitle();
            }
            if ($item->getContent()) {
                $newsItem->contents = $item->getContent();
            }
            if ($item->getDate()) {
                $newsItem->date->setTimestamp($item->getDate()->getTimestamp());
                $newsItem->date->setTimezone($item->getDate()->getTimezone());
            }
            if ($item->getUrl()) {
                $newsItem->source = $item->getUrl();
            }
            $newsItem->slug = $newsItem->guid;
            $newsItem->rubric = $rssImportItem->rubric;
            foreach ($rssImportItem->subjects as $subject) {
                $newsItem->subjects->attach($subject);
            }
        }
    }
}
