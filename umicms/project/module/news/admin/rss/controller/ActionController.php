<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rss\controller;

use umi\orm\persister\TObjectPersisterAware;
use umicms\project\admin\api\controller\DefaultRestActionController;
use umicms\project\module\news\api\NewsModule;
use umicms\project\module\news\api\object\NewsRssImportScenario;

/**
 * Контроллер операций.
 */
class ActionController extends DefaultRestActionController
{
    /**
     * @var NewsModule $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsModule $api
     */
    public function __construct(NewsModule $api)
    {
        $this->api = $api;
    }

    /**
     * Запускает импорт RSS-ленты.
     */
    protected function actionImportFromRss()
    {
        /**
         * @var NewsRssImportScenario $scenario
         */
        $scenario = $this->getEditedObject($this->getIncomingData());

        $this->api->importRss($scenario);
        $this->getObjectPersister()->commit();

        return '';
    }
}
