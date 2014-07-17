<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\megaindex\controller;

use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\TActionController;
use umicms\project\module\seo\model\MegaindexModel;

/**
 * Контроллер операций с API Мегаиндекса
 */
class ActionController extends BaseController
{
    use TActionController;

    /**
     * Возвращает данные отчета {@link http://api.megaindex.ru/description/siteAnalyze «Видимость сайта»}
     * @return array
     */
    protected function actionSiteAnalyze()
    {
        return $this->getModel()->queryApi('siteAnalyze');
    }

    /**
     * Возвращает данные отчета {@link http://api.megaindex.ru/description/get_backlinks «Получение ссылок на сайт»}
     * @return array
     */
    protected function actionGetBacklinks()
    {
        return $this->getModel()->queryApi('get_backlinks');
    }

    /**
     * Создает и возвращает модель для отправки запросов к API Мегаиндекса
     * @return MegaindexModel
     */
    protected function getModel()
    {
        return new MegaindexModel($this->getComponent());
    }
}
