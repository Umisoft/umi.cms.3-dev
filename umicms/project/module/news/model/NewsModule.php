<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\model;

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
use umicms\project\module\news\model\collection\NewsItemCollection;
use umicms\project\module\news\model\collection\NewsRubricCollection;
use umicms\project\module\news\model\collection\NewsSubjectCollection;
use umicms\project\module\news\model\collection\NewsRssImportScenarioCollection;
use umicms\project\module\news\model\object\NewsItem;
use umicms\project\module\news\model\object\NewsRubric;
use umicms\project\module\news\model\object\NewsSubject;
use umicms\project\module\news\model\object\NewsRssImportScenario;

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
     * @return NewsRssImportScenarioCollection
     */
    public function rssImport()
    {
        return $this->getCollection('newsRssImportScenario');
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
     * @param NewsRssImportScenario $newsRssImportScenario сценарий импорта RSS-ленты
     * @throws RuntimeException если не удалось выполнить импорт
     * @return $this
     */
    public function importRss(NewsRssImportScenario $newsRssImportScenario)
    {
        try {
            $xml = \GuzzleHttp\get($newsRssImportScenario->rssUrl)
                ->xml(['object' => false]);
        } catch (\Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $newsRssImportScenario->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromSimpleXml($xml);

        foreach ($rssFeed->getRssItems() as $item) {
            $this->importRssItem($item, $newsRssImportScenario);
        }

        return $this;
    }

    /**
     * Импортирует новость из RSS.
     * @param RssItem $item
     * @param NewsRssImportScenario $newsRssImportScenario
     */
    protected function importRssItem(RssItem $item, NewsRssImportScenario $newsRssImportScenario)
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
                $newsItem->announcement = $item->getContent();
            }
            if ($item->getDate()) {
                $newsItem->date->setTimestamp($item->getDate()->getTimestamp());
                $newsItem->date->setTimezone($item->getDate()->getTimezone());
            }
            if ($item->getUrl()) {
                $newsItem->source = $item->getUrl();
            }
            $newsItem->slug = $newsItem->guid;
            $newsItem->rubric = $newsRssImportScenario->rubric;

            foreach ($newsRssImportScenario->subjects as $subject) {
                $newsItem->subjects->attach($subject);
            }
        }
    }

}
