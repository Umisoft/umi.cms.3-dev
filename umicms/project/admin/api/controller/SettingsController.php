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
use umicms\hmvc\component\AdminComponent;
use umicms\hmvc\controller\BaseController;
use umicms\orm\collection\IApplicationHandlersAware;

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

        list($collections, $resources) = $this->getCollectionsSettings();
        return $this->createViewResponse(
            'settings',
            [
                'modules' => $this->getModulesSettings(),
                'collections' => $collections,
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
        $resources = [];

        foreach ($collectionNames as $collectionName) {
            $collection = $this->getCollectionManager()->getCollection($collectionName);
            $collections[] = $collection;
            if ($collection instanceof IApplicationHandlersAware && $collection->hasHandler('admin')) {
                $resources[] = [
                    'collection' => $collectionName,
                    'uri' => '/' . str_replace('.', '/', $collection->getHandlerPath('admin'))
                ];
            }

        }

        return [$collections, $resources];
    }

    /**
     * Возвращает настройки модулей.
     * @return array
     */
    protected function getModulesSettings()
    {
        $modules = [];

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
            }

            $modules[] = [
                'name'       => $moduleName,
                'path'       => $module->getPath(),
                'settings'   => $module->getSettings()
                        ->get(AdminComponent::OPTION_ADMIN_INTERFACE) ? : [],
                'components' => $components
            ];
        }

        return $modules;
    }

    protected function assembleResourceUri(AdminComponent $module, AdminComponent $component)
    {

        $moduleUri = $this->getComponent()->getRouter()->assemble('component', [
                'component' => $module->getName()
            ]);

        $componentUri = $module->getRouter()->assemble('component', [
                'component' => $component->getName()
            ]);

        return $moduleUri . $componentUri;
    }

}


