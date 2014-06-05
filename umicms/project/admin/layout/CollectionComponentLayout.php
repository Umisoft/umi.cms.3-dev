<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout;

use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\project\admin\api\component\CollectionApiComponent;
use umicms\project\admin\layout\control\CreateObjectControl;
use umicms\project\admin\layout\control\EditObjectControl;
use umicms\project\admin\layout\control\TableControl;
use umicms\project\admin\layout\control\TreeControl;

/**
 * Билдер сетки для стандартного компонента по управлению ORM-коллекцией.
 */
class CollectionComponentLayout extends ComponentLayout
{
    /**
     * @var CollectionApiComponent $component
     */
    protected $component;
    /**
     * @var ICmsCollection $collection
     */
    protected $collection;

    /**
     * Конструктор.
     * @param CollectionApiComponent $component.
     */
    public function __construct(CollectionApiComponent $component) {
        $this->component = $component;
        $this->collection = $component->getCollection();

        $this->params['collectionName'] = $component->getCollection()->getName();

        $this->configureActions();
        $this->configureSideBar();
        $this->configureEmptyContextControls();
        $this->configureSelectedContextControls();
    }

    /**
     * Конфигурирует Sidebar компонента в зависимости от коллекции компонента.
     * @return $this
     */
    protected function configureSideBar()
    {
        if ($this->collection instanceof SimpleHierarchicCollection) {
            $tree = new TreeControl($this->component);
            $this->addSideBarControl('tree', $tree);
        }

        return $this;
    }

    /**
     * Конфигурирует REST-экшены для компонента.
     * @return $this
     */
    protected function configureActions()
    {
        foreach ($this->component->getQueryActions() as $name => $action) {
            $this->addAction($name, $action);
        }

        foreach ($this->component->getModifyActions() as $name => $action) {
            $this->addAction($name, $action);
        }

        return $this;
    }

    /**
     * Конфигурирует контролы контентной области для пустого контекста.
     * @return $this
     */
    protected function configureEmptyContextControls()
    {
        $this->addEmptyContextControl('filter',  new TableControl($this->component));
        $this->addEmptyContextControl('createForm',  new CreateObjectControl($this->component));
    }

    /**
     * Конфигурирует контролы контентной области для выбранного контекста.
     * @return $this
     */
    protected function configureSelectedContextControls()
    {
        $this->addSelectedContextControl('editForm', new EditObjectControl($this->component));
    }
}
 