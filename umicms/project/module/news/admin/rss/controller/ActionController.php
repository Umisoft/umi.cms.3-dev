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

use umi\i18n\translator\ITranslator;
use umicms\hmvc\component\admin\collection\ActionController as CollectionActionController;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\news\model\object\NewsRssImportScenario;

/**
 * Контроллер операций.
 */
class ActionController extends CollectionActionController
{
    /**
     * @var NewsModule $module
     */
    protected $module;

    /**
     * Конструктор.
     * @param ITranslator $translator
     * @param NewsModule $module
     */
    public function __construct(ITranslator $translator, NewsModule $module)
    {
        $this->module = $module;
        parent::__construct($translator);
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

        $this->module->importRss($scenario);
        $this->commit();

        return '';
    }
}
