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
use umi\rss\TRssFeedAware;
use umicms\api\repository\BaseObjectRepository;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\RssItem;

/**
 * Репозиторий для работы с коллекцией импортируемых RSS-лент.
 */
class RssItemRepository extends BaseObjectRepository implements IRssFeedAware, ILocalizable
{
    use TRssFeedAware;
    use TLocalizable;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'rssItem';

    /**
     * Возвращает селектор для выбора импортируемых RSS-лент.
     * @return CmsSelector|RssItem[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает RSS-ленту по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить RSS-ленту
     * @return RssItem
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
     * @return RssItem
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
     * @return RssItem
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает RSS-ленту на удаление.
     * @param RssItem $item
     * @return $this
     */
    public function delete(RssItem $item)
    {
        $this->getCollection()->delete($item);

        return $this;
    }

    /**
     * Импортирует новости из RSS-ленты.
     * @param string $guid GUID импортируемой RSS-ленты
     * @param NewsItemRepository $newsItemRepository
     * @throws RuntimeException
     * @return $this
     */
    public function importRss($guid, NewsItemRepository $newsItemRepository)
    {
        $rssFeed = $this->get($guid);
        try {
            $xml = \GuzzleHttp\get($rssFeed->rssUrl)
                ->xml(['object' => false]);
        } catch (Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $rssFeed->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromXml($xml->asXML());

        $items = $rssFeed->getRssItems();
        foreach ($items as $item) {
            $this->importRssItem($item, $newsItemRepository);
        }

        return $this;
    }

    /**
     * Импортирует новость из RSS-ленты.
     * @param \umi\rss\RssItem $item
     * @param NewsItemRepository $newsItemRepository
     */
    protected function importRssItem($item, NewsItemRepository $newsItemRepository)
    {
        $newsItem = $newsItemRepository->add();
        if ($item->getTitle()) {
            $newsItem->displayName = $item->getTitle();
        }
        if ($item->getContent()) {
            $newsItem->contents = $item->getTitle();
        }
        if ($item->getDate()) {
            $newsItem->date = $item->getDate();
        }
        /*if ($item->getUrl()) {
            $newsItem->date = $item->getDate();
        }*/
    }
}
