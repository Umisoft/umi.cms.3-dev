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
use umicms\api\BaseHierarchicCollectionApi;
use umicms\api\IPublicApi;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\project\module\structure\model\StructureElement;

/**
 * API для работы со структурой.
 */
class StructureApi extends BaseHierarchicCollectionApi implements IPublicApi
{
    /**
     * @var string $collectionName имя коллекции для хранения структуры
     */
    public $collectionName = 'structure';

    /**
     * @var StructureElement $currentElement
     */
    protected $currentElement;

    /**
     * Устанавливает текущий элемент структуры
     * @internal
     * @param StructureElement $element
     */
    public function setCurrentElement(StructureElement $element) {
        $this->currentElement = $element;
    }

    /**
     * Возвращает текущий элемент структуры.
     * @throws RuntimeException если текущий элемент не был установлен
     * @return StructureElement
     */
    public function getCurrentElement() {
        if (!$this->currentElement) {
            throw new RuntimeException($this->translate(
                'Current structure element is not detected.'
            ));
        }
        return $this->currentElement;
    }

    /**
     * Проверяет, был ли установлен текущий элемент структуры.
     * @return bool
     */
    public function hasCurrentElement() {
        return !is_null($this->currentElement);
    }

    /**
     * Возвращает элемент по GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить элемент
     * @return StructureElement
     */
    public function getElement($guid)
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

}
