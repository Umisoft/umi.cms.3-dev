<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umi\orm\exception\IException;
use umi\orm\selector\ISelector;
use umicms\api\BaseCollectionApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\module\news\object\NewsItem;
use umicms\project\module\news\object\NewsRubric;
use umicms\project\module\news\object\NewsSubject;

/**
 * API для работы с новостями
 */
class NewsItemApi extends BaseCollectionApi
{
    /**
     * {@inheritdoc}
     */
    public $collectionName = 'NewsItem';

    /**
     * Возвращает новость по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить новость
     * @return NewsItem
     */
    public function get($guid) {

        try {
            return $this->getCollection()->get($guid);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news item by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает новость по ее последней части ЧПУ.
     * @param string $slug последняя часть ЧПУ новости
     * @throws NonexistentEntityException если новость с заданной последней частью ЧПУ не существует
     * @return NewsItem
     */
    public function getBySlug($slug) {
        $selector = $this->select()
            ->where(NewsItem::FIELD_SLUG)
            ->equals($slug);

        $item = $selector->getResult()->fetch();

        if (!$item instanceof NewsItem) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news item by slug "{slug}".',
                    ['slug' => $slug]
                )
            );
        }

        return $item;
    }

    /**
     * Возвращает селектор для выбора новостей рубрики.
     * @param NewsRubric $rubric рубрика
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function getNewsByRubric(NewsRubric $rubric, $onlyActive = true)
    {
        return $this->select($onlyActive)
            ->where(NewsItem::FIELD_RUBRIC)->equals($rubric);
    }

    /**
     * Возвращает селектор для выбора новостей сюжета.
     * @param NewsSubject $subject
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function getNewsBySubject(NewsSubject $subject, $onlyActive = true)
    {
        return $this->select($onlyActive)
            ->where(NewsItem::FIELD_SUBJECTS)->equals($subject);
    }
}
