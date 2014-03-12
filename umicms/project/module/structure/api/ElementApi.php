<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api;

use umi\orm\exception\IException;
use umi\orm\metadata\IObjectType;
use umicms\api\BaseHierarchicCollectionApi;
use umicms\api\IPublicApi;
use umicms\exception\NonexistentEntityException;
use umicms\project\admin\api\TTrashAware;
use umicms\project\module\structure\object\StructureElement;

/**
 * API для работы с элементами структуры.
 */
class ElementApi extends BaseHierarchicCollectionApi implements IPublicApi
{
    use \umicms\project\admin\api\TTrashAware;

    /**
     * @var string $collectionName имя коллекции для хранения структуры
     */
    public $collectionName = 'structure';

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
        } catch(IException $e) {
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
    public function getById($id) {

        try {
            return $this->getCollection()->getById($id);
        } catch(IException $e) {
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
     * Возвращает элемент по URL.
     * @param string $url
     * @throws NonexistentEntityException если не удалось получить элемент
     * @return StructureElement
     */
    public function getByUrl($url)
    {
        try {
            return $this->getElementByUrl($url);
        } catch(IException $e) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find element by url "{url}".',
                    ['url' => $url]
                ),
                0,
                $e
            );
        }
    }

    /**
     * Добавляет элемент.
     * @param string $slug
     * @param StructureElement $parent
     * @return StructureElement
     */
    public function add($slug, StructureElement $parent = null)
    {
        return $this->getCollection()->add($slug, IObjectType::BASE, $parent);
    }

    /**
     * Помечает элемент на удаление.
     * @param StructureElement $element
     */
    public function delete(StructureElement $element) {

        $this->getCollection()->delete($element);
    }

}
