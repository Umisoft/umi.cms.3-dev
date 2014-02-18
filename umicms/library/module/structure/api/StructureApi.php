<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\module\structure\api;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\ISimpleHierarchicCollection;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\metadata\field\special\MaterializedPathField;
use umi\orm\selector\ISelector;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\module\structure\model\StructureElement;

/**
 * API для работы со структурой.
 */
class StructureApi implements ICollectionManagerAware, ILocalizable
{
    use TCollectionManagerAware;
    use TLocalizable;

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
     * Возвращает селектор для выбора элементов структуры.
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function select($onlyActive = true)
    {
        $select = $this
            ->getStructureCollection()
            ->select();
        if ($onlyActive) {
            $select
                ->where('active')
                ->equals(true);
        }

        return $select;
    }

    /**
     * Возвращает элемент по GUID.
     * @param string $guid
     * @return StructureElement
     */
    public function getElement($guid)
    {
        $page = $this
            ->getStructureCollection()
            ->get($guid);

        return $page;
    }

    /**
     * Возвращает селектор для выбора детей указанного элемента, либо от корня (на один уровень вложенности).
     * @param StructureElement|null $element эелемент, либо null, если нужна выборка от корня
     * @param bool $onlyActive учитывать активность
     * @return ISelector
     */
    public function selectChildren(StructureElement $element = null, $onlyActive = true)
    {
        return $this
            ->select($onlyActive)
                ->where(StructureElement::FIELD_PARENT)->equals($element)
            ->orderBy(StructureElement::FIELD_ORDER);
    }

    /**
     * Возвращает селектор для выбора родителей элемента.
     * @param StructureElement $element
     * @return ISelector
     */
    public function selectAncestry(StructureElement $element)
    {
        return $this
            ->getStructureCollection()
            ->selectAncestry($element);
    }

    /**
     * Возвращает селектор для выбора потомков указанного элемента, либо от корня.
     * @param StructureElement|null $element эелемент, либо null, если нужна выборка от корня
     * @param int|null $depth глубина выбора потомков, по умолчанию выбираются на всю глубину
     * @param bool $onlyActive учитывать активность
     * @throws InvalidArgumentException
     * @return ISelector
     */
    public function selectDescendants(StructureElement $element = null, $depth = null, $onlyActive = true)
    {
        if (!is_null($depth) && !is_int($depth) && $depth < 0) {
            throw new InvalidArgumentException($this->translate(
                'Cannot select descendants. Invalid argument "depth" value.'
            ));
        }

        if ($depth == 1) {
            return $this->selectChildren($element, $onlyActive);
        }

        $selector = $this->select($onlyActive);

        if ($element) {
            $selector
                ->where(StructureElement::FIELD_MPATH)
                ->like($element->getMaterializedPath() . MaterializedPathField::MPATH_SEPARATOR . '%');
        }

        if ($depth) {
            $selector
                ->where(StructureElement::FIELD_HIERARCHY_LEVEL)
                ->equalsOrLess($element->getLevel() + $depth);
        }
        $selector->orderBy(StructureElement::FIELD_ORDER);

        return $selector;
    }

    /**
     * Перемещает элемент в ветку после указанного.
     * Если ветка не указана, элемент будет помещен в корень.
     * Если предшественник не указан, элемент будет помещен в начало ветки.
     * @param StructureElement $element перемещаемый элемент
     * @param StructureElement|null $branch ветка, в которую будет перемещен элемент
     * @param StructureElement|null $previousSibling элемент, предшествующий перемещаемому
     * @return self
     */
    public function move(StructureElement $element, StructureElement $branch = null, StructureElement $previousSibling = null)
    {
        $this
            ->getStructureCollection()
            ->move($element, $branch, $previousSibling);

        return $this;
    }

    /**
     * Возвращает структуру.
     * @return ISimpleHierarchicCollection
     */
    protected function getStructureCollection()
    {
        return $this->getCollectionManager()->getCollection($this->collectionName);
    }
}
