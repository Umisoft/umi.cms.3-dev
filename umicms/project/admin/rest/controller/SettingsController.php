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
            'Profile' => $this->translate('Profile'),
            'Little' => $this->translate('Little'),
            'Big' => $this->translate('Big'),
            'Listed' => $this->translate('Listed'),
            'Dynamic' => $this->translate('Dynamic'),
            'Remember my choice' => $this->translate('Remember my choice'),
            'Nothing found' => $this->translate('Nothing found'),
            'Loading' => $this->translate('Loading'),
            'Select file' => $this->translate('Select file'),
            'File manager' => $this->translate('File manager'),
            'Moved to trash' => $this->translate('Moved to trash'),
            'Failed to move in the trash' => $this->translate('Failed to move in the trash'),
            'Restored' => $this->translate('Restored'),
            'Not restored' => $this->translate('Not restored'),
            'The object will be deleted permanently, continue anyway' => $this->translate('The object will be deleted permanently, continue anyway'),
            'Delete' => $this->translate('Delete'),
            'Cancel' => $this->translate('Cancel'),
            'Successfully removed' => $this->translate('Successfully removed'),
            'Failed to delete' => $this->translate('Failed to delete'),
            'Waiting' => $this->translate('Waiting'),
            'Saved' => $this->translate('Saved'),
            'Close' => $this->translate('Close'),
            'All' => $this->translate('All'),
            'Of' => $this->translate('Of'),
            'Not valid' => $this->translate('Not valid'),
            'For' => $this->translate('For'),
            'Modules are not available' => $this->translate('Modules are not available'),
            'Module' => $this->translate('Module'),
            'Components' => $this->translate('Components'),
            'Component' => $this->translate('Component'),
            'Not found' => $this->translate('Not found'),
            'Resource' => $this->translate('Resource'),
            'Incorrect' => $this->translate('Incorrect'),
            'With' => $this->translate('With'),
            'Object' => $this->translate('Object'),
            'The actions for this context is not available' => $this->translate('The actions for this context is not available'),
            'Action' => $this->translate('Action'),
            'Not available for the selected context' => $this->translate('Not available for the selected context'),
            'The changes were not saved' => $this->translate('The changes were not saved'),
            'Transition:unsaved changes' => $this->translate('Transition:unsaved changes'),
            'Stay on the page' => $this->translate('Stay on the page'),
            'Continue without saving' => $this->translate('Continue without saving'),
            'Internal Server Error' => $this->translate('Internal Server Error'),
            'Unknown error' => $this->translate('Unknown error'),
        ];
    }

}


