<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\settings\admin\controller;

use umicms\hmvc\controller\BaseCmsController;
use umicms\project\module\settings\admin\component\SettingsComponent;

/**
 * Контроллер списка настроек.
 */
class SettingsController extends BaseCmsController
{

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'components'     => $this->getComponentsInfo(),
            ]
        );
    }

    /**
     * Возвращает информацию о компонентах.
     * @return array
     */
    protected function getComponentsInfo()
    {
        $application = $this->getComponent();
        $applicationInfo = $this->getComponentInfo($application);

        return isset($applicationInfo['components']) ? $applicationInfo['components'] : [];
    }

    /**
     * Возвращает информацию о компонентах на всю глубину с учетом проверки прав.
     * @param SettingsComponent $component компонент
     * @param SettingsComponent $context контескт, в котором проверяются права
     * @return array
     */
    protected function getComponentInfo(SettingsComponent $component, SettingsComponent $context = null)
    {
        $componentInfo = [];

        if (is_null($context) || $this->getContext()->getDispatcher()->checkPermissions($context, $component)) {

            $componentInfo = $component->getComponentInfo();

            $childComponentsInfo = [];

            foreach ($component->getChildComponentNames() as $childComponentName) {
                $childComponent = $component->getChildComponent($childComponentName);

                if ($childComponentInfo = $this->getComponentInfo($childComponent, $component)) {
                    $childComponentsInfo[] = $childComponentInfo;
                }
            }

            if ($childComponentsInfo) {
                $componentInfo['components'] = $childComponentsInfo;
            }
        }

        return $componentInfo;
    }

}
 