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
use umicms\orm\collection\ICmsCollection;
use umicms\project\admin\component\AdminComponent;
use umicms\hmvc\controller\BaseController;

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
        /**
         * @var AdminComponent $application
         */
        $application = $this->getComponent();
        $applicationInfo = $application->getComponentInfo();
        $modules = isset($applicationInfo['components']) ? $applicationInfo['components'] : [];

        list($collections, $resources) = $this->getCollectionsSettings();

        return $this->createViewResponse(
            'settings',
            [
                'modules'     => $modules,
                'collections' => $collections,
                'resources'   => $resources
            ]
        );
    }

    /**
     * Возвращает настройки коллекций.
     * @return array
     */
    protected function getCollectionsSettings()
    {
        $collectionNames = $this->getCollectionManager()
            ->getList();

        $collections = [];
        $resources = [];

        foreach ($collectionNames as $collectionName) {
            /**
             * @var ICmsCollection $collection
             */
            $collection = $this->getCollectionManager()
                ->getCollection($collectionName);
            $collections[] = $collection;
            if ($collection->hasHandler('admin')) {
                $componentInfo = explode('.', $collection->getHandlerPath('admin'));
                $resources[] = [
                    'collection' => $collectionName,
                    'module'     => isset($componentInfo[0]) ? $componentInfo[0] : null,
                    'component'  => isset($componentInfo[1]) ? $componentInfo[1] : null
                ];
            }

        }

        return [$collections, $resources];
    }

    protected function assembleResourceUri(AdminComponent $module, AdminComponent $component)
    {

        $moduleUri = $this->getComponent()
            ->getRouter()
            ->assemble(
            'component',
            [
                'component' => $module->getName()
            ]
        );

        $componentUri = $module->getRouter()
            ->assemble(
            'component',
            [
                'component' => $component->getName()
            ]
        );

        return $moduleUri . $componentUri;
    }

}


