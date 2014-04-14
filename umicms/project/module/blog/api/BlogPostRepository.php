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
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\blog\api\object\BlogTag;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

/**
 * Репозиторий для работы с постами блога.
 */
class BlogPostRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'blogPost';

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
     * Возвращает селектор для выбора постов.
     * @return CmsSelector|BlogPost[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает пост по его GUID.
     * @param string $guid
     * @throws NonexistentEntityException
     * @return BlogPost
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog post by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает пост по его id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить пост
     * @return BlogPost
     */
    public function getById($id)
    {

        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog post by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет пост.
     * @return BlogPost
     */
    public function add()
    {
        return $this->getCollection()->add();
    }

    /**
     * Помечает пост на удаление.
     * @param BlogPost $post
     * @return $this
     */
    public function delete(BlogPost $post)
    {
        $this->getCollection()->delete($post);

        return $this;
    }

    /**
     * Возвращает пост по его последней части ЧПУ.
     * @param string $slug последняя часть ЧПУ поста
     * @throws NonexistentEntityException если новость с заданной последней частью ЧПУ не существует
     * @return BlogPost
     */
    public function getBySlug($slug)
    {
        $selector = $this->select()
            ->where(BlogPost::FIELD_PAGE_SLUG)
            ->equals($slug);

        $item = $selector->getResult()->fetch();

        if (!$item instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog post by slug "{slug}".',
                    ['slug' => $slug]
                )
            );
        }

        return $item;
    }

    /**
     * Возвращает селектор для выбора постов категории.
     * @param BlogCategory $category категория
     * @return CmsSelector|BlogPost[]
     */
    public function getNewsByRubric(BlogCategory $category)
    {
        return $this->select()
            ->where(BlogPost::FIELD_CATEGORY)
            ->equals($category);
    }

    /**
     * Возвращает селектор для выбора постов по тэгу.
     * @param BlogTag $tag
     * @return CmsSelector|BlogPost[]
     */
    public function getNewsBySubject(BlogTag $tag)
    {
        return $this->select()
            ->where(BlogPost::FIELD_TAGS)
            ->equals($tag);
    }

    /**
     * Возвращает список резервных копий объекта.
     * @param BlogPost $post
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(BlogPost $post)
    {
        return $this->backupRepository->getList($post);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param BlogPost $post
     * @param int $backupId идентификатор резервной копии
     * @return BlogPost
     */
    public function getBackup(BlogPost $post, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($post, $backupId);
    }
}
