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
use umicms\project\module\news\api\object\NewsSubject;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

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
     * Возвращает селектор для выбора сюжетов.
     * @return CmsSelector|NewsSubject[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает сюжет по его GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function get($guid) {
        try {
            return $this->getCollection()->get($guid);
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
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
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
     * @throws NonexistentEntityException если не удалось получить сюжет
     * @return NewsSubject
     */
    public function getBySlug($slug) {
        $selector = $this->select()
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

    /**
     * Возвращает список резервных копий объекта.
     * @param NewsSubject $newsSubject
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(NewsSubject $newsSubject)
    {
        return $this->backupRepository->getList($newsSubject);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param NewsSubject $newsSubject
     * @param int $backupId идентификатор резервной копии
     * @return NewsSubject
     */
    public function getBackup(NewsSubject $newsSubject, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($newsSubject, $backupId);
    }
}
