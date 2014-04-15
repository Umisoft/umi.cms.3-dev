<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api;

use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogTag;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

/**
 * Репозиторий для работы с тэгами.
 */
class BlogTagRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'blogTag';

    /**
     * @var BackupRepository $backupRepository
     */
    protected $backupRepository;

    /**
     * Конструктор.
     * @param BackupRepository $backupRepository
     */
    public function __construct(BackupRepository $backupRepository)
    {
        $this->backupRepository = $backupRepository;
    }

    /**
     * Возвращает селектор для выбора тэгов.
     * @return CmsSelector|BlogTag[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает тэг по его GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить тэг
     * @return BlogTag
     */
    public function get($guid) {
        try {
            return $this->getCollection()->get($guid);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog tag by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает тэг по его id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить тэг
     * @return BlogTag
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog tag by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает тэг по его последней части ЧПУ.
     * @param string $slug последняя часть ЧПУ
     * @throws NonexistentEntityException если не удалось получить тэг
     * @return BlogTag
     */
    public function getBySlug($slug) {
        $selector = $this->select()
            ->where(BlogTag::FIELD_PAGE_SLUG)
            ->equals($slug);

        $tag = $selector->getResult()->fetch();

        if (!$tag instanceof BlogTag) {
            throw new NonexistentEntityException($this->translate(
                'Cannot find blog tag by slug "{slug}".',
                ['slug' => $slug]
            ));
        }

        return $tag;
    }

    /**
     * Помечает тэг на удаление.
     * @param BlogTag $tag
     * @return $this
     */
    public function delete(BlogTag $tag) {

        $this->getCollection()->delete($tag);

        return $this;
    }

    /**
     * Возвращает список резервных копий объекта.
     * @param BlogTag $tag
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(BlogTag $tag)
    {
        return $this->backupRepository->getList($tag);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param BlogTag $tag
     * @param int $backupId идентификатор резервной копии
     * @return BlogTag
     */
    public function getBackup(BlogTag $tag, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($tag, $backupId);
    }
}
