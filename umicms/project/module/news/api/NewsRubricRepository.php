<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umi\orm\metadata\IObjectType;
use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\THierarchicAwareRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\news\api\object\NewsRubric;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;

/**
 * Репозиторий для работы с новостными рубриками
 */
class NewsRubricRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;
    use THierarchicAwareRepository;

    /**
     * {@inheritdoc}
     */
    public $collectionName = 'newsRubric';

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
     * Возвращает селектор для выбора новостных рубрик.
     * @return CmsSelector|NewsRubric[]
     */
    public function select() {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает новостую рубрику по ее GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить рубрику
     * @return NewsRubric
     */
    public function get($guid) {

        try {
            return $this->getCollection()->get($guid);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает рубрику по ее id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить рубрику
     * @return NewsRubric
     */
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает новостую рубрику по ее Uri
     * @param string $uri
     * @throws NonexistentEntityException
     * @return NewsRubric
     */
    public function getByUri($uri)
    {
        try {
            return $this->selectByUri($uri);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find news rubric by uri "{uri}".',
                    ['uri' => $uri]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет рубрику новостей.
     * @param string $slug
     * @param NewsRubric $parent
     * @return NewsRubric
     */
    public function add($slug, NewsRubric $parent = null)
    {
        return $this->getCollection()->add($slug, IObjectType::BASE, $parent);
    }

    /**
     * Помечает рубрику на удаление.
     * @param NewsRubric $rubric
     * @return $this
     */
    public function delete(NewsRubric $rubric)
    {

        $this->getCollection()->delete($rubric);

        return $this;
    }

    /**
     * Перемещает рубрику в иерархии.
     * Если ветка не указана, рубрика перемещается в корень.
     * Если предшественник не указан, рубрика перемещается в начало ветки.
     * @param NewsRubric $rubric перемещаемая рубрика
     * @param NewsRubric|null $branch ветка, в которую будет перемещена рубрика
     * @param NewsRubric|null $previousSibling рубрика, после которой будет помещена перемещаемая рубрика
     * @return $this
     */
    public function move(NewsRubric $rubric, NewsRubric $branch = null, NewsRubric $previousSibling = null)
    {
        $this->getCollection()->move($rubric, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает список резервных копий объекта.
     * @param NewsRubric $newsRubric
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(NewsRubric $newsRubric)
    {
        return $this->backupRepository->getList($newsRubric);
    }

    /**
     * Возвращает резервную копию объекта.
     * @param NewsRubric $newsRubric
     * @param int $backupId идентификатор резервной копии
     * @return NewsRubric
     */
    public function getBackup(NewsRubric $newsRubric, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($newsRubric, $backupId);
    }
}
