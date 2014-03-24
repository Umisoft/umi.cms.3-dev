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
use umicms\hmvc\controller\BaseSecureController;
use umicms\orm\collection\ICmsCollection;
use umicms\project\admin\api\ApiApplication;

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
        $applicationInfo = $application->getComponentInfo();

        return isset($applicationInfo['components']) ? $applicationInfo['components'] : [];
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


