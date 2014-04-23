<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\settings\controller;

use umi\acl\IAclResource;
use umicms\hmvc\controller\BaseSecureController;
use umicms\project\admin\settings\component\SettingsComponent;
use umicms\project\admin\settings\SettingsApplication;

/**
 * Контроллер списка настроек.
 */
class SettingsController extends BaseSecureController
{

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'components'     => $this->getModulesInfo(),
            ]
        );
    }

    /**
     * Возвращает информацию о модулях.
     * @return array
     */
    protected function getModulesInfo()
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
 