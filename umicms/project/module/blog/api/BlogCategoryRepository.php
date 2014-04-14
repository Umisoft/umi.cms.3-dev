<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api;

use umi\orm\metadata\IObjectType;
use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\THierarchicAwareRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

/**
 * Репозиторий для работы с категориями блога.
 */
class BlogCategoryRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;
    use THierarchicAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'blogCategory';

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
     * Возвращает селектор для выбора категорий блога.
     * @return CmsSelector|BlogCategory[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает категорию блога по ее GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить категорию
     * @return BlogCategory
     */
    public function get($guid) {

        try {
            return $this->getCollection()->get($guid);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog category by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает категорию по ее id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить категорию
     * @return BlogCategory
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog category by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает категорию по ее Uri
     * @param string $uri
     * @throws NonexistentEntityException
     * @return BlogCategory
     */
    public function getByUri($uri)
    {
        try {
            return $this->selectByUri($uri);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog category by uri "{uri}".',
                    ['uri' => $uri]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет категорию блога.
     * @param string $slug
     * @param BlogCategory $parent
     * @return BlogCategory
     */
    public function add($slug, BlogCategory $parent = null)
    {
        return $this->getCollection()->add($slug, IObjectType::BASE, $parent);
    }

    /**
     * Помечает рубрику на удаление.
     * @param BlogCategory $category
     * @return $this
     */
    public function delete(BlogCategory $category)
    {
        $this->getCollection()->delete($category);

        return $this;
    }

    /**
     * Перемещает категорию в иерархии.
     * Если ветка не указана, категория перемещается в корень.
     * Если предшественник не указан, категория перемещается в начало ветки.
     * @param BlogCategory $category перемещаемая категория
     * @param BlogCategory|null $branch ветка, в которую будет перемещена категория
     * @param BlogCategory|null $previousSibling категория, после которой будет помещена перемещаемая категория
     * @return $this
     */
    public function move(BlogCategory $category, BlogCategory $branch = null, BlogCategory $previousSibling = null)
    {
        $this->getCollection()->move($category, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает список резервных копий объекта.
     * @param BlogCategory $blogCategory
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(BlogCategory $blogCategory)
    {
        return $this->backupRepository->getList($blogCategory);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param BlogCategory $blogCategory
     * @param int $backupId идентификатор резервной копии
     * @return BlogCategory
     */
    public function getBackup(BlogCategory $blogCategory, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($blogCategory, $backupId);
    }
}
