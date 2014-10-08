<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\widget;

use umi\orm\collection\ICollection;
use umicms\exception\InvalidArgumentException;
use umicms\exception\RuntimeException;
use umicms\hmvc\view\CmsView;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\selector\CmsSelector;
use umicms\orm\selector\TSelectorConfigurator;

/**
 * Базовый класс виджета вывода деревьев.
 * Применяет условия выборки для дерева.
 */
abstract class BaseTreeWidget extends BaseCmsWidget
{
    use TSelectorConfigurator;

    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'tree';
    /**
     * @var string|CmsHierarchicObject $parentNode родительская нода или GUID родительской ноды. Если не указано, строится полное дерево
     */
    public $parentNode;
    /**
     * @var int $depth глубина вложения. Если не указано, строится на всю глубину вложенности
     */
    public $depth;
    /**
     * @var string $orderBy имя поля, по которому происходит сортировка потомков одного уровня
     */
    public $orderBy = CmsHierarchicObject::FIELD_ORDER;
    /**
     * @var string $direction направление, по которому происходит сортировка потомков одного уровня
     */
    public $direction = CmsSelector::ORDER_ASC;
    /**
     * @var array $options настройки выборки
     * <ul>
     * <li>fields - имена полей, указанные через запятую, которые будут загружены для объектов</li>
     * </ul>
     */
    public $options = [];
    /**
     * @var bool $fullyLoad признак необходимости загружать все свойства объектов списка.
     * Список полей для загрузки, занный опциями, при значении true игнорируется.
     */
    public $fullyLoad;

    /**
     * Возвращает коллекцию, для которой строится дерево.
     * @return CmsHierarchicCollection
     */
    abstract protected function getCollection();

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\hmvc\view\CmsTreeView $tree представление дерева
     *
     * @throws RuntimeException
     * @return CmsView
     */
    public function __invoke()
    {
        $collection = $this->getCollection();

        if (!$collection instanceof CmsHierarchicCollection) {
            throw new RuntimeException($this->translate(
                'Cannot create tree. Collection is not hierarchical'
            ));
        }

        $parentNode = $this->getParentNode($collection);
        /**
         * @var CmsSelector $selector
         */
        $selector = $collection->selectDescendants($parentNode, $this->depth, $this->orderBy, $this->direction);

        $this->configureSelector($selector);
        $this->applySelectorConditions($selector);

        return $this->createTreeResult($this->template, $selector);
    }

    /**
     * Расширяет выборку потомков дополнительными условиями.
     * @param CmsSelector $selector
     */
    protected function configureSelector(CmsSelector $selector)
    {

    }

    /**
     * Пременяет условия выборки.
     * @param CmsSelector $selector
     * @return CmsSelector
     */
    protected function applySelectorConditions(CmsSelector $selector)
    {
        if (!$this->fullyLoad) {
            $fields = ICmsObject::FIELD_DISPLAY_NAME;
            if (isset($this->options['fields'])) {
                $fields = $fields . ',' . $this->options['fields'];
            }
            $this->applySelectorSelectedFields($selector, $fields);
        }

        return $selector;
    }

    /**
     * Возвращает родительскую ноду. Если был указан GUID получает объект.
     * @param ICollection $collection коллекция для получения родительской ноды
     * @throws InvalidArgumentException в случае если родительская нода не иерархический объект
     * @return CmsHierarchicObject
     */
    private function getParentNode($collection)
    {
        $parentNode = $this->parentNode;

        if (is_string($parentNode)) {
            $parentNode = $collection->get($parentNode);
        }

        if (!is_null($parentNode) && !$parentNode instanceof CmsHierarchicObject) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'parentNode',
                        'class' => 'CmsHierarchicObject'
                    ]
                )
            );
        }

        return $parentNode;
    }
}
 