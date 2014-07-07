<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\admin\recycle\layout;

use umi\orm\collection\ICollectionManager;
use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\action\Action;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\hmvc\url\IUrlManager;
use umicms\orm\collection\behaviour\IRecyclableCollection;

class RecycleComponentLayout extends AdminComponentLayout
{
    /**
     * Менеджер коллекций.
     * @var ICollectionManager $collectionManager
     */
    private $collectionManager;

    /**
     * Список коллекций.
     * @var array $listCollection
     */
    private $listCollection = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager, ICollectionManager $collectionManager)
    {
        parent::__construct($component);

        $this->component = $component;
        $this->collectionManager = $collectionManager;

        $this->dataSource = [
            'type' => 'static',
            'objects' => $this->getListCollection()
        ];

        $this->addSideBarControl('menu',  new AdminControl($this->component));

        $this->configureEmptyContextControls();

        $this->configureDynamicControl();
    }

    /**
     * Возвращает массив доступных action.
     * @return array
     */
    public function getListCollection()
    {
        $listCollection = $this->collectionManager->getList();

        foreach ($listCollection as $collectionName) {
            $collection = $this->collectionManager->getCollection($collectionName);
            if ($collection instanceof IRecyclableCollection && $collection->selectTrashed()->limit('1')->result()->count()) {
                $this->listCollection[] = [
                    'id' => $collectionName,
                    'displayName' => $this->component->translate('collection:' . $collectionName)
                ];
            }
        }

        return $this->listCollection;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureEmptyContextControls()
    {
        $control = new AdminControl($this->component);
        if (isset($this->listCollection[0])) {
            $control->params['slug'] = $this->listCollection[0]['id'];
        }

        $this->addEmptyContextControl('redirect', $control);
    }

    /**
     * Конфигурирует динамический контрол.
     */
    private function configureDynamicControl()
    {
        $dynamicControl = new AdminControl($this->component);
        $dynamicControl->params['action'] = 'getTableControl';

        $this->addSelectedContextControl('filter', $dynamicControl);
    }
}
