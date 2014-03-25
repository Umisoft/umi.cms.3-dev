<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsItem;
use umicms\project\module\news\api\object\NewsRubric;
use umicms\project\module\news\api\object\NewsSubject;

/**
 * Репозиторий для работы с новостями
 */
class NewsItemRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'newsItem';


    /**
     * Возвращает селектор для выбора новостей.
     * @return CmsSelector|NewsItem[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает новость по ее GUID.
     * @param string $guid
     * @throws NonexistentEntityException
     * @return NewsItem
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
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
     * Возвращает новость по ее id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить новость
     * @return NewsItem
     */
    public function getById($id)
    {

        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news item by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет новость.
     * @return NewsItem
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает новость на удаление.
     * @param NewsItem $item
     * @return $this
     */
    public function delete(NewsItem $item)
    {
        $this->getCollection()->delete($item);

        return $this;
    }

    /**
     * Возвращает новость по ее последней части ЧПУ.
     * @param string $slug последняя часть ЧПУ новости
     * @throws NonexistentEntityException если новость с заданной последней частью ЧПУ не существует
     * @return NewsItem
     */
    public function getBySlug($slug)
    {
        $selector = $this->select()
            ->where(NewsItem::FIELD_PAGE_SLUG)
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
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsByRubric(NewsRubric $rubric)
    {
        return $this->select()
            ->where(NewsItem::FIELD_RUBRIC)
            ->equals($rubric);
    }

    /**
     * Возвращает селектор для выбора новостей сюжета.
     * @param NewsSubject $subject
     * @return CmsSelector|NewsItem[]
     */
    public function getNewsBySubject(NewsSubject $subject)
    {
        return $this->select()
            ->where(NewsItem::FIELD_SUBJECTS)
            ->equals($subject);
    }
}
