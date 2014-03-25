<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\orm\metadata\IObjectType;
use umi\orm\selector\ISelector;
use umicms\api\repository\BaseObjectRepository;
use umicms\api\repository\THierarchicAwareRepository;
use umicms\api\repository\TRecycleAwareRepository;
use umicms\exception\InvalidArgumentException;
use umicms\exception\NonexistentEntityException;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\service\api\BackupRepository;
use umicms\project\module\service\api\object\Backup;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\module\structure\api\object\SystemPage;

/**
 * Репозиторий для работы с элементами структуры.
 */
class ElementRepository extends BaseObjectRepository
{
    use TRecycleAwareRepository;
    use THierarchicAwareRepository;

    /**
     * @var BackupRepository $backupRepository
     */
    protected $backupRepository;

    public function __construct(BackupRepository $backupRepository)
    {
        $this->backupRepository = $backupRepository;
    }

    /**
     * @var string $collectionName имя коллекции для хранения структуры
     */
    public $collectionName = 'structure';

    /**
     * Возвращает селектор для выбора элементов структуры.
     * @return CmsSelector|StructureElement[]
     */
    public function select()
    {
        return $this->getCollection()->select();
    }

    /**
     * Возвращает элемент по GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить элемент
     * @return StructureElement
     */
    public function get($guid)
    {
        try {
            return $this->getCollection()->get($guid);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by guid "{guid}".',
                    ['guid' => $guid]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает элемент по id.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить элемент
     * @return StructureElement
     */
    public function getById($id)
    {
        try {
            return $this->getCollection()->getById($id);
        } catch (\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by id "{id}".',
                    ['id' => $id]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает элемент по Uri.
     * @param string $uri
     * @throws NonexistentEntityException если не удалось получить элемент
     * @return StructureElement
     */
    public function getByUri($uri)
    {
        try {
            return $this->getByUri($uri);
        } catch(\Exception $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by uri "{uri}".',
                    ['uri' => $uri]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Возвращает системную страницу по пути ее компонента-обработчика
     * @param string $componentPath путь ее компонента-обработчика
     * @throws NonexistentEntityException если такой страницы нет
     * @return SystemPage
     */
    public function getSystemPageByComponentPath($componentPath)
    {
        $page = $this->selectSystem()
            ->where(SystemPage::FIELD_COMPONENT_PATH)
            ->equals($componentPath)
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$page instanceof SystemPage) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by component path "{path}".',
                    ['path' => $componentPath]
                )
            );
        }

        return $page;
    }

    /**
     * Добавляет элемент.
     * @param string $slug
     * @param StructureElement $parent
     * @return StructureElement
     */
    public function add($slug, StructureElement $parent = null)
    {
        return $this->getCollection()
            ->add($slug, IObjectType::BASE, $parent);
    }

    /**
     * Помечает элемент на удаление.
     * @param StructureElement $element
     * @return $this
     */
    public function delete(StructureElement $element)
    {
        $this->getCollection()->delete($element);

        return $this;
    }

    /**
     * Перемещает элемент структуры в иерархии.
     * Если ветка не указана, элемент перемещается в корень.
     * Если предшественник не указан, элемент перемещается в начало ветки.
     * @param StructureElement $element перемещаемый элемент
     * @param StructureElement|null $branch ветка, в которую будет перемещен элемент
     * @param StructureElement|null $previousSibling элемент, после которой будет помещен перемещаемый элемент
     * @return $this
     */
    public function move(
        StructureElement $element,
        StructureElement $branch = null,
        StructureElement $previousSibling = null
    ) {
        $this->getCollection()->move($element, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает селектор для выбора системных страниц.
     * @return ISelector|SystemPage[]
     */
    public function selectSystem()
    {
        return $this->select()->types(['system']);
    }


    /**
     * Возвращает список резервных копий объекта
     * @param StructureElement $element
     * @return CmsSelector|Backup[] $object
     */
    public function getBackupList(StructureElement $element)
    {
        return $this->backupRepository->getList($element);
    }

    /**
     * Возвращает резервную копию объекта
     * @param StructureElement $element
     * @param int $backupId идентификатор резервной копии
     * @throws InvalidArgumentException
     * @return StructureElement
     */
    public function getBackup(StructureElement $element, $backupId)
    {
        return $this->backupRepository->wakeUpBackup($element, $backupId);
    }


}
