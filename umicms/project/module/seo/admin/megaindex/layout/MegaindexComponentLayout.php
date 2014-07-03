<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\megaindex\layout;

use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\action\Action;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;
use umicms\hmvc\url\IUrlManager;

/**
 * Билдер сетки административного компонента Megaindex.
 */
class MegaindexComponentLayout extends AdminComponentLayout
{
    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        $this->component = $component;

        $this->addAction('getResource', new Action(
            $urlManager->getAdminComponentResourceUrl($this->component) . '/action/{id}')
        );

        $this->dataSource = [
            'type' => 'static',
            'objects' => $this->getChildActionResources()
        ];

        $this->addSideBarControl('menu',  new AdminControl($this->component));

        $this->configureEmptyContextControls();

        $this->configureDynamicControl();
    }

    /**
     * Возвращает массив доступных action.
     * @return array
     */
    public function getChildActionResources()
    {
        $result = [];

        foreach ($this->component->getQueryActions() as $name => $action) {
            $result[] = [
                'id' => $name,
                'displayName' => $this->component->translate(
                        'action:' . $name . ':displayName'
                    )
            ];
        }

        return $result;
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
     * Конфигурирует динамический контрол.
     */
    private function configureDynamicControl()
    {
        $dynamicControl = new AdminControl($this->component);
        $dynamicControl->params['action'] = 'getResource';

        $this->addSelectedContextControl('dynamic', $dynamicControl);
    }
}
 