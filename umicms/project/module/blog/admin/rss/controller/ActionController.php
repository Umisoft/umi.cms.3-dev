<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\admin\rss\controller;

use umicms\project\admin\rest\controller\DefaultRestActionController;
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
