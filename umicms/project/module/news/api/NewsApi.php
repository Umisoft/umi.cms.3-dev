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
use umi\orm\selector\ISelector;
use umi\rss\IRssFeed;
use umi\rss\IRssFeedAware;
use umi\rss\TRssFeedAware;
use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\module\news\api\object\NewsItem;
use umicms\project\module\news\api\object\NewsRubric;
use umicms\project\module\news\api\object\NewsSubject;
use umicms\project\module\news\api\object\RssImportItem;

/**
 * Публичное API модуля "Новости"
 */
class NewsApi extends BaseComplexApi implements IPublicApi, IUrlManagerAware, IRssFeedAware
{
    use TUrlManagerAware;
    use TRssFeedAware;

    /**
     * Возвращает репозиторий для работы с новостями.
     * @return NewsItemRepository
     */
    public function news()
    {
        return $this->getApi('umicms\project\module\news\api\NewsItemRepository');
    }

    /**
     * Возвращает репозиторий для работы с новостными рубриками.
     * @return NewsRubricRepository
     */
    public function rubric()
    {
        return $this->getApi('umicms\project\module\news\api\NewsRubricRepository');
    }

    /**
     * Возвращает репозиторий для работы с новостными сюжетами.
     * @return NewsSubjectRepository
     */
    public function subject()
    {
        return $this->getApi('umicms\project\module\news\api\NewsSubjectRepository');
    }

    /**
     * Возвращает репозиторий для работы с импортируемыми RSS-лентами.
     * @return RssImportItemRepository
     */
    public function rss()
    {
        return $this->getApi('umicms\project\module\news\api\RssImportItemRepository');
    }

    /**
     * Возвращает селектор для выборки новостей.
     * @param int $limit максимальное количество новостей
     * @return ISelector
     */
    public function getNews($limit = null)
    {
        $news = $this->news()->select()
            ->orderBy(NewsItem::FIELD_DATE, ISelector::ORDER_DESC);

        if ($limit) {
            $news->limit($limit);
        }

        return $news;
    }

    /**
     * Строит RSS-ленту.
     * @param string $title заголовок RSS-ленты
     * @param string $description описание RSS-ленты
     * @param ISelector|NewsItem[] $newsSelector список новостей
     * @return IRssFeed
     */
    public function getNewsRssFeed($title, $description, ISelector $newsSelector)
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
     * @param NewsRubric[] $rubrics список GUID рубрик новостей
     * @param int $limit максимальное количество новостей
     * @return ISelector
     */
    public function getRubricNews(array $rubrics = [], $limit = null)
    {
        $news = $this->getNews($limit);

        $news->begin(IFieldConditionGroup::MODE_OR);
        foreach ($rubrics as $rubric) {
            $news->where(NewsItem::FIELD_RUBRIC)->equals($rubric);
        }
        $news->end();

        return $news;
    }

    /**
     * Возвращает селектор для выборки новостей указанных сюжетов.
     * @param array $subjectGuids список GUID сюжетов новостей
     * @param int $limit максимальное количество новостей
     * @return ISelector
     */
    public function getSubjectNews($subjectGuids = [], $limit = null)
    {
        $news = $this->getNews($limit);

        if (count($subjectGuids)) {
            $news->where(NewsItem::FIELD_SUBJECTS . ISelector::FIELD_SEPARATOR . NewsSubject::FIELD_GUID)
                ->in($subjectGuids);
        }

        return $news;
    }

    /**
     * Возвращает селектор для выборки новостных рубрик в указанной рубрике.
     * @param NewsRubric|null $parentRubric GUID рубрики
     * @param int $limit максимальное количество рубрик
     * @return ISelector
     */
    public function getRubrics(NewsRubric $parentRubric = null, $limit = null)
    {
        $rubrics = $this->rubric()->selectChildren($parentRubric);

        if ($limit) {
            $rubrics->limit($limit);
        }

        return $rubrics;
    }

    /**
     * Возвращает селектор для выборки новостных сюжетов.
     * @param int $limit максимальное количество сюжетов
     * @return ISelector
     */
    public function getSubjects($limit = null)
    {
        $rubrics = $this->subject()->select();

        if ($limit) {
            $rubrics->limit($limit);
        }

        return $rubrics;
    }

    /**
     * Выполнение импорта новостей из RSS-ленты.
     * @param RssImportItem $rssImportItem импортируемая RSS-лента
     */
    public function importRss(RssImportItem $rssImportItem)
    {
        $this->rss()->importRss($rssImportItem, $this->news());
    }

}
