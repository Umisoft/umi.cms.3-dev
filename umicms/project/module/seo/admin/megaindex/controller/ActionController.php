<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\megaindex\controller;

use umicms\exception\InvalidArgumentException;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;
use umicms\project\admin\api\controller\DefaultRestActionController;
use umicms\project\module\seo\model\MegaindexModel;

/**
 * Контроллер операций с API Мегаиндекса
 */
class ActionController extends DefaultRestActionController
{
    /**
     * Возвращает данные отчета {@link http://api.megaindex.ru/description/siteAnalyze «Видимость сайта»}
     * @return array
     */
    public function actionSiteAnalyze()
    {
        return $this->getModel()->queryApi('siteAnalyze');
    }

    /**
     * Возвращает данные отчета {@link http://api.megaindex.ru/description/get_backlinks «Получение ссылок на сайт»}
     * @return array
     */
    public function actionGetBacklinks()
    {
        return $this->getModel()->queryApi('get_backlinks');
    }

    /**
     * Создает и возвращает модель для отправки запросов к API Мегаиндекса
     * @return MegaindexModel
     * @throws InvalidArgumentException
     */
    protected function getModel()
    {
        $component = $this->getComponent();

        $login = $component->getSetting(MegaindexModel::MEGAINDEX_LOGIN);
        $password = $component->getSetting(MegaindexModel::MEGAINDEX_PASSWORD);
        $siteUrl = $component->getSetting(MegaindexModel::MEGAINDEX_SITE_URL);

        if (is_null($login)) {
            throw new InvalidArgumentException($this->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_LOGIN]
            ));
        }
        if (is_null($password)) {
            throw new InvalidArgumentException($this->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_PASSWORD]
            ));
        }
        if (is_null($siteUrl)) {
            throw new InvalidArgumentException($this->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_SITE_URL]
            ));
        }
        return new MegaindexModel($login, $password, $siteUrl);
    }

    /**
     * @return DefaultQueryAdminComponent
     */
    protected function getComponent()
    {
        return $this->getContext()->getComponent();
    }
}
