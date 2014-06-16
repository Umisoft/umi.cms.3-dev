<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout;

use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\hmvc\component\admin\collection\CollectionComponent;
use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\control\CreateObjectControl;
use umicms\hmvc\component\admin\layout\control\EditObjectControl;
use umicms\hmvc\component\admin\layout\control\TableControl;
use umicms\hmvc\component\admin\layout\control\TreeControl;

/**
 * Билдер сетки для стандартного компонента по управлению ORM-коллекцией.
 */
class CollectionComponentLayout extends AdminComponentLayout
{
    /**
     * @var CollectionComponent $component
     */
    protected $component;
    /**
     * @var ICmsCollection $collection
     */
    protected $collection;

    /**
     * Конструктор.
     * @param AdminComponent $component .
     * @throws RuntimeException если компонент не CollectionComponent
     */
    public function __construct(AdminComponent $component) {
        if (!$component instanceof CollectionComponent) {
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
        if ($this->collection instanceof CmsHierarchicCollection) {
            $tree = new TreeControl($this->component);
            $this->addSideBarControl('tree', $tree);
        }
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

        if ($this->collection instanceof CmsHierarchicCollection) {
            $this->addSelectedContextControl('createForm', new CreateObjectControl($this->component));
        }
    }
}
 