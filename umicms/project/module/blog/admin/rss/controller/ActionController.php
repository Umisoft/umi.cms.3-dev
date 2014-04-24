<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\admin\rss\controller;

use umicms\project\admin\api\controller\DefaultRestActionController;
use umicms\project\module\blog\api\BlogModule;
use umicms\project\module\blog\api\object\BlogRssImportScenario;

/**
 * Контроллер операций.
 */
class ActionController extends DefaultRestActionController
{
    /**
     * @var BlogModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param BlogModule $api
     */
    public function __construct(BlogModule $api)
    {
        $this->api = $api;
    }

    /**
     * Запускает импорт RSS-ленты.
     */
    protected function actionImportFromRss()
    {
        /**
         * @var BlogRssImportScenario $scenario
         */
        $scenario = $this->getEditedObject($this->getIncomingData());

        $this->api->importRss($scenario);
        $this->getObjectPersister()->commit();

        return '';
    }
}
