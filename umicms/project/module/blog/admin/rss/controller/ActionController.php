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

use umicms\hmvc\component\admin\collection\ActionController as CollectionActionController;
use umicms\project\module\blog\model\BlogModule;
use umicms\project\module\blog\model\object\BlogRssImportScenario;

/**
 * Контроллер операций.
 */
class ActionController extends CollectionActionController
{
    /**
     * @var BlogModule $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param BlogModule $module
     */
    public function __construct(BlogModule $module)
    {
        $this->module = $module;
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

        $this->module->importRss($scenario);
        $this->getObjectPersister()->commit();

        return '';
    }
}
