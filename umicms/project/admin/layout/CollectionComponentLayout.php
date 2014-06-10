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

use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\project\admin\rest\component\CollectionApiComponent;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\layout\control\CreateObjectControl;
use umicms\project\admin\layout\control\EditObjectControl;
use umicms\project\admin\layout\control\TableControl;
use umicms\project\admin\layout\control\TreeControl;

/**
 * Билдер сетки для стандартного компонента по управлению ORM-коллекцией.
 */
class CollectionComponentLayout extends AdminComponentLayout
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
     * @param AdminComponent $component .
     * @throws RuntimeException если компонент не CollectionApiComponent
     */
    public function __construct(AdminComponent $component) {
        if (!$component instanceof CollectionApiComponent) {
            throw new RuntimeException('Wrong component for collection component layout.');
        }

        $this->collection = $component->getCollection();

        $this->params['collectionName'] = $component->getCollection()->getName();

        parent::__construct($component);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    protected function configureEmptyContextControls()
    {
        $this->addEmptyContextControl('filter',  new TableControl($this->component));
        $this->addEmptyContextControl('createForm',  new CreateObjectControl($this->component));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSelectedContextControls()
    {
        $this->addSelectedContextControl('editForm', new EditObjectControl($this->component));

        if ($this->collection instanceof SimpleHierarchicCollection) {
            $this->addSelectedContextControl('createForm', new CreateObjectControl($this->component));
        }
    }
}
 