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
use umicms\project\module\news\object\NewsSubject;

/**
 * Репозиторий для работы с новостными сюжетами.
 */
class NewsSubjectRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'newsSubject';

    /**
     * Возвращает селектор для выбора сюжетов.
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @return CmsSelector|NewsSubject[]
     */
    public function select($onlyPublic = true) {
        return $this->selectAll($onlyPublic);
    }

    /**
     * Возвращает сюжет по его GUID
     * @param string $guid
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function get($guid, $onlyPublic = true) {
        try {
            return $this->selectByGuid($guid, $onlyPublic);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news subject by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает сюжет по его id.
     * @param int $id
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function getById($id, $onlyPublic = true) {

        try {
            return $this->selectById($id, $onlyPublic);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news subject by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает сюжет по его последней части ЧПУ
     * @param string $slug последняя часть ЧПУ
     * @param bool $onlyPublic выбирать только публично доступные объекты
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function getBySlug($slug, $onlyPublic = true) {
        $selector = $this->select($onlyPublic)
            ->where(NewsSubject::FIELD_PAGE_SLUG)
            ->equals($slug);

        $subject = $selector->getResult()->fetch();

        if (!$subject instanceof NewsSubject) {
            throw new NonexistentEntityException($this->translate(
                'Cannot find news subject by slug "{slug}".',
                ['slug' => $slug]
            ));
        }

        return $subject;
    }

    /**
     * Помечает сюжет на удаление.
     * @param NewsSubject $subject
     * @return $this
     */
    public function delete(NewsSubject $subject) {

        $this->getCollection()->delete($subject);

        return $this;
    }
}
