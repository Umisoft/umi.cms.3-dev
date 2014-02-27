<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\base\component\AdminComponent;
use umicms\base\controller\BaseController;

/**
 * Контроллер настроек административной панели.
 */
class SettingsController extends BaseController implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {

        list($modules, $resources) = $this->getModulesSettings();

        return $this->createViewResponse(
            'settings',
            [
                'modules' => $modules,
                'collections' => $this->getCollectionsSettings(),
                'resources' => $resources
            ]
        );
    }

    /**
     * Возвращает настройки коллекций.
     * @return array
     */
    protected function getCollectionsSettings()
    {
        $collectionNames = $this->getCollectionManager()->getList();

        $collections = [];

        foreach ($collectionNames as $collectionName) {
            $collections[] = $this->getCollectionManager()->getCollection($collectionName);
        }

        return $collections;
    }

    /**
     * Возвращает настройки модулей.
     * @return array
     */
    protected function getModulesSettings()
    {
        $modules = [];
        $resources = [];
        /**
         * @var AdminComponent $application
         */
        $application = $this->getComponent();

        foreach ($application->getChildComponentNames() as $moduleName) {
            /**
             * @var AdminComponent $module
             */
            $module = $application->getChildComponent($moduleName);

            $components = [];
            foreach ($module->getChildComponentNames() as $componentName) {
                $component = $module->getChildComponent($componentName);
                /**
                 * @var AdminComponent $component
                 */
                $components[] = [
                    'name'     => $componentName,
                    'path'     => $component->getPath(),
                    'settings' => $component->getSettings()
                            ->get(AdminComponent::OPTION_ADMIN_INTERFACE) ? : []
                ];

                if ($component->getSettings()->has(AdminComponent::OPTION_COLLECTION_NAME)) {
                    $resources[] = [
                        'collection' => $component->getSettings()->get(AdminComponent::OPTION_COLLECTION_NAME),
                        'uri' => $this->assembleResourceUri($module, $component)
                    ];
                }
            }

            $modules[] = [
                'name'       => $moduleName,
                'path'       => $module->getPath(),
                'settings'   => $module->getSettings()
                        ->get(AdminComponent::OPTION_ADMIN_INTERFACE) ? : [],
                'components' => $components
            ];
        }

        return [$modules, $resources];
    }

    protected function assembleResourceUri(AdminComponent $module, AdminComponent $component)
    {

        $moduleUri = $this->getComponent()->getRouter()->assemble('component', [
            'component' => $module->getName()
        ]);

        $componentUri = $module->getRouter()->assemble('component', [
            'component' => $component->getName()
        ]);

        return $this->getContext()->getBaseUrl() . $moduleUri . $componentUri;
    }

}


