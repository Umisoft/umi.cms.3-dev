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

use umicms\exception\InvalidArgumentException;
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
        return [
            'data' => $this->getModel()->queryApi('get_backlinks'),
            'headers' => [
                'На какой странице расположена ссылка'
            ]
        ];
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
}
