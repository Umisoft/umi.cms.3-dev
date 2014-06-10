<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\admin\rss\controller;

use umi\orm\persister\TObjectPersisterAware;
use umicms\project\admin\rest\controller\DefaultRestActionController;
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
