<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\api\controller;

use umi\acl\IAclResource;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\hmvc\controller\BaseSecureController;
use umicms\orm\collection\ICmsCollection;
use umicms\project\admin\api\ApiApplication;
use umicms\project\admin\component\AdminComponent;

/**
 * Контроллер настроек административной панели.
 */
class ApiSettingsController extends BaseSecureController implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'modules'     => $this->getModulesInfo(),
                'collections' => $this->getCollectionsInfo()
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
         * @var ApiApplication $application
         */
        $application = $this->getComponent();
        $applicationInfo = $this->getComponentInfo($application);

        return isset($applicationInfo['components']) ? $applicationInfo['components'] : [];
    }

    /**
     * Возвращает информацию о компонентах на всю глубину с учетом проверки прав.
     * @param AdminComponent $component компонент
     * @param AdminComponent $context контескт, в котором проверяются права
     * @return array
     */
    protected function getComponentInfo(AdminComponent $component, AdminComponent $context = null)
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

    /**
     * Возвращает информацию о коллекциях.
     * @return array
     */
    protected function getCollectionsInfo()
    {
        $collectionNames = $this->getCollectionManager()
            ->getList();

        $collections = [];

        foreach ($collectionNames as $collectionName) {
            /**
             * @var ICmsCollection $collection
             */
            $collection = $this->getCollectionManager()
                ->getCollection($collectionName);
            $collections[] = $collection;
        }

        return $collections;
    }

}


