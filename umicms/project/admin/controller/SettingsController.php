<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\controller;

use umicms\base\component\Component;
use umicms\base\controller\BaseController;

/**
 * Контроллер настроек административной панели.
 */
class SettingsController extends BaseController
{

    const OPTION_ADMIN_INTERFACE = 'interface';
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $modules = [];
        /**
         * @var Component $application
         */
        $application = $this->getComponent();

        foreach($application->getChildComponentList() as $moduleName) {
            /**
             * @var Component $module
             */
            $module = $application->getChildComponent($moduleName);

            $components = [];
            foreach ($module->getChildComponentList() as $componentName) {
                $component = $module->getChildComponent($componentName);
                /**
                 * @var Component $component
                 */
                $components[] = [
                    'name' => $componentName,
                    'settings' => $component->getSettings()->get(self::OPTION_ADMIN_INTERFACE) ?: []
                ];
            }

            $modules[] = [
                'name' => $moduleName,
                'settings' => $module->getSettings()->get(self::OPTION_ADMIN_INTERFACE) ?: [],
                'components' => $components
            ];
        }

        return $this->createViewResponse(
            'settings',
            ['modules' => $modules]
        );
    }

}


