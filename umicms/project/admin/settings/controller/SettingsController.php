<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\settings\controller;

use umi\acl\IAclResource;
use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\admin\settings\component\SettingsComponent;
use umicms\project\admin\settings\SettingsApplication;

/**
 * Контроллер списка настроек.
 */
class SettingsController extends BaseAccessRestrictedController
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
        /**
         * @var SettingsApplication $application
         */
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

        if (
            !$component instanceof IAclResource || is_null($context) ||
            $this->getContext()->getDispatcher()->checkPermissions($context, $component)
        ) {
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
 