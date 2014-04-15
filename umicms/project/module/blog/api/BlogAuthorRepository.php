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
use umicms\project\module\blog\api\object\BlogAuthor;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

/**
 * Репозиторий для работы с авторами блога.
 */
class BlogAuthorRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'blogAuthor';

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
     * Возвращает селектор для выбора авторов.
     * @return CmsSelector|BlogAuthor[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает автора по его GUID.
     * @param string $guid
     * @throws NonexistentEntityException
     * @return BlogAuthor
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog author by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает автора по его id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить автора
     * @return BlogAuthor
     */
    public function getById($id)
    {

        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog author by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет автора.
     * @return BlogAuthor
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает автора на удаление.
     * @param BlogAuthor $author
     * @return $this
     */
    public function delete(BlogAuthor $author)
    {
        $this->getCollection()->delete($author);

        return $this;
    }

    /**
     * Возвращает автора по его последней части ЧПУ.
     * @param string $slug последняя часть ЧПУ автора
     * @throws NonexistentEntityException если автор с заданной последней частью ЧПУ не существует
     * @return BlogAuthor
     */
    public function getBySlug($slug)
    {
        $selector = $this->select()
            ->where(BlogAuthor::FIELD_PAGE_SLUG)
            ->equals($slug);

        $item = $selector->getResult()->fetch();

        if (!$item instanceof BlogAuthor) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog author by slug "{slug}".',
                    ['slug' => $slug]
                )
            );
        }

        return $item;
    }

    /**
     * Возвращает селектор для выбора постов автора.
     * @param BlogAuthor $author категория
     * @return CmsSelector|BlogPost[]
     */
    public function getPostByRubric(BlogAuthor $author)
    {
        return $this->select()
            ->where(BlogPost::FIELD_AUTHOR)
            ->equals($author);
    }

    /**
     * Возвращает список резервных копий объекта.
     * @param BlogAuthor $author
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(BlogAuthor $author)
    {
        return $this->backupRepository->getList($author);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param BlogAuthor $author
     * @param int $backupId идентификатор резервной копии
     * @return BlogPost
     */
    public function getBackup(BlogAuthor $author, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($author, $backupId);
    }
}
