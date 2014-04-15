<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umi\orm\selector\condition\IFieldConditionGroup;
use umi\rss\IRssFeed;
use umi\rss\IRssFeedAware;
use umi\rss\RssItem;
use umi\rss\TRssFeedAware;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\collection\NewsItemCollection;
use umicms\project\module\news\api\collection\NewsRubricCollection;
use umicms\project\module\news\api\collection\NewsSubjectCollection;
use umicms\project\module\news\api\collection\RssImportScenarioCollection;
use umicms\project\module\news\api\object\NewsItem;
use umicms\project\module\news\api\object\NewsRubric;
use umicms\project\module\news\api\object\NewsSubject;
use umicms\project\module\news\api\object\RssImportScenario;

/**
 * Модуль "Новости".
 */
class NewsModule extends BaseModule implements IRssFeedAware, IUrlManagerAware
{
    use TRssFeedAware;
    use TUrlManagerAware;

    /**
     * Возвращает коллекцию новостей.
     * @return NewsItemCollection
     */
    public function news()
    {
        return $this->getCollection('newsItem');
    }

    /**
     * Возвращает коллекцию новостных рубрик.
     * @return NewsRubricCollection
     */
    public function rubric()
    {
        return $this->getCollection('newsRubric');
    }

    /**
     * Возвращает коллекцию новостных сюжетов.
     * @return NewsSubjectCollection
     */
    public function subject()
    {
        return $this->getCollection('newsSubject');
    }

    /**
     * Возвращает коллекцию сценариев RSS-импортов новостей.
     * @return RssImportScenarioCollection
     */
    public function rssImport()
    {
        return $this->getCollection('rssImportScenario');
    }

    /**
     * Возвращает селектор для выборки новостей.
     * @return CmsSelector|NewsItem[]
     */
    public function getNews()
    {
        $news = $this->news()->select()
            ->orderBy(NewsItem::FIELD_DATE, CmsSelector::ORDER_DESC);

        return $news;
    }

    /**
     * Строит RSS-ленту.
     * @param string $title заголовок RSS-ленты
     * @param string $description описание RSS-ленты
     * @param CmsSelector|NewsItem[] $newsSelector список новостей
     * @return IRssFeed
     */
    public function getNewsRssFeed($title, $description, CmsSelector $newsSelector)
    {
        $rssFeed = $this->createRssFeed(
            $this->getUrlManager()->getProjectUrl(true),
            $title,
            $description
        );

        foreach ($newsSelector as $newsItem) {
            $rssFeed->addItem()
                ->setTitle($newsItem->h1)
                ->setContent($newsItem->announcement)
                ->setUrl($newsItem->getPageUrl(true))
                ->setDate($newsItem->date);
        }

        return $rssFeed;
    }

    /**
     * Возвращает селектор для выборки новостей указанных рубрик.
     * @param NewsRubric[] $rubrics список рубрик новостей
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsByRubrics(array $rubrics = [])
    {
        $news = $this->getNews();

        $news->begin(IFieldConditionGroup::MODE_OR);
        foreach ($rubrics as $rubric) {
            $news->where(NewsItem::FIELD_RUBRIC)->equals($rubric);
        }
        $news->end();

        return $news;
    }

    /**
     * Возвращает селектор для выборки новостей указанных сюжетов.
     * @param NewsSubject[] $subjects список сюжетов новостей
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsBySubjects(array $subjects = [])
    {
        $news = $this->getNews();

        $news->begin(IFieldConditionGroup::MODE_OR);
        foreach ($subjects as $subject) {
            $news->where(NewsItem::FIELD_SUBJECTS)->equals($subject);
        }
        $news->end();

        return $news;
    }

    /**
     * Возвращает селектор для выборки новостных рубрик в указанной рубрике.
     * @param NewsRubric|null $parentRubric GUID рубрики
     * @return CmsSelector|NewsRubric[]
     */
    public function getRubrics(NewsRubric $parentRubric = null)
    {
        $rubrics = $this->rubric()->selectChildren($parentRubric);

        return $rubrics;
    }

    /**
     * Возвращает селектор для выборки новостных сюжетов.
     * @return CmsSelector|NewsSubject[]
     */
    public function getSubjects()
    {
        return $this->subject()->select();
    }

    /**
     * Выполняет импорт новостей из внешней RSS-ленты.
     * @param RssImportScenario $rssImportScenario сценарий импорта RSS-ленты
     * @throws RuntimeException если не удалось выполнить импорт
     * @return $this
     */
    public function importRss(RssImportScenario $rssImportScenario)
    {
        try {
            $xml = \GuzzleHttp\get($rssImportScenario->rssUrl)
                ->xml(['object' => false]);
        } catch (\Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $rssImportScenario->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromSimpleXml($xml);

        foreach ($rssFeed->getRssItems() as $item) {
            $this->importRssItem($item, $rssImportScenario);
        }

        return $this;
    }

    /**
     * Импортирует новость из RSS.
     * @param RssItem $item
     * @param RssImportScenario $rssImportScenario
     */
    protected function importRssItem(RssItem $item, RssImportScenario $rssImportScenario)
    {
        try {
            $this->news()->getNewsBySource($item->getUrl());
        } catch (NonexistentEntityException $e) {
            $newsItem = $this->news()->add();
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
            $newsItem->rubric = $rssImportScenario->rubric;

            foreach ($rssImportScenario->subjects as $subject) {
                $newsItem->subjects->attach($subject);
            }
        }
    }

}
