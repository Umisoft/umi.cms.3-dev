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

use umicms\hmvc\component\admin\layout\action\Action;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Билдер сетки для компонента, группирующего компоненты настроек.
 */
class SettingsGroupComponentLayout extends AdminComponentLayout
{
    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        parent::__construct($component);


        $this->addAction('getSettings', new Action(
            $urlManager->getAdminComponentResourceUrl($this->component) . '/{id}')
        );

        $this->dataSource = [
            'type' => 'static',
            'objects' => $this->getChildSettingResources()
        ];
    }

    /**
     * Формирует список дочерних настроечных компонентов.
     */
    protected function getChildSettingResources()
    {
        $result = [];
        foreach ($this->component->getChildComponentNames() as $name) {
            /**
             * @var AdminComponent $childComponent
             */
            $childComponent = $this->component->getChildComponent($name);

            $result[] = [
                'id' => $name,
                'displayName' => $childComponent->translate(
                    'component:' . $name . ':displayName'
                )
            ];
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSideBar()
    {
        $this->addSideBarControl('menu',  new AdminControl($this->component));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSelectedContextControls()
    {
        $this->addSelectedContextControl('editForm', $this->createDynamicControl());
    }

    /**
     * {@inheritdoc}
     */
    protected function configureEmptyContextControls()
    {
        $control = new AdminControl($this->component);
        $childComponentNames = $this->component->getChildComponentNames();

        if (isset($childComponentNames[0])) {
            $control->params['slug'] = $childComponentNames[0];
        }

        $this->addEmptyContextControl('redirect', $control);
    }

    /**
     * @return AdminControl
     */
    private function createDynamicControl()
    {
        $dynamicControl = new AdminControl($this->component);
        $dynamicControl->params['action'] = 'getSettings';

        return $dynamicControl;
    }
}
 