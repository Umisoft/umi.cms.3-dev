<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest\controller;

use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\admin\rest\RestApplication;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Контроллер настроек административной панели.
 */
class SettingsController extends BaseCmsController implements ICollectionManagerAware
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
                'collections' => $this->getCollectionsInfo(),
                'i18n'        => $this->getLabels()
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
         * @var RestApplication $application
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
            !$component->isSkippedInDock() &&
            (is_null($context) || $this->getContext()->getDispatcher()->checkPermissions($context, $component))
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
        $collectionNames = $this->getCollectionManager()->getList();
        $collections = [];

        foreach ($collectionNames as $collectionName) {
            $collections[] = $this->getCollectionManager()->getCollection($collectionName);
        }

        return $collections;
    }

    /**
     * Возвращает лейблы для интерфейса в текущей локали
     * @return array
     */
    protected function getLabels()
    {
        return [
            'Open site in new tab' => $this->translate('Open site in new tab'),
            'Logout' => $this->translate('Logout'),
            'Remember my choice' => $this->translate('Remember my choice'),
            'Nothing found' => $this->translate('Nothing found'),
            'Loading' => $this->translate('Loading')
        ];
    }

}


