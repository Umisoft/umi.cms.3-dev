<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\yandex\controller;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\component\admin\BaseController;
use umicms\hmvc\component\admin\TActionController;
use umicms\project\module\seo\model\YandexModel;

/**
 * Контроллер операций с API Яндекс.Вебмастер.
 */
class ActionController extends BaseController
{
    use TActionController;

    /**
     * @var array $options настройки Яндекс.Вебмастер.
     */
    public $options;
    /**
     * @var int $hostId идентификатор хоста.
     */
    protected $hostId;

    /**
     * Возвращает список доступных сайтов
     * @return array
     */
    protected function actionHosts()
    {
        return $this->getModel()
            ->getHosts();
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    protected function actionHost()
    {
        return $this->getModel()
            ->getHostStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    protected function actionIndexed()
    {
        return $this->getModel()
            ->getIndexedStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    protected function actionLinks()
    {
        return $this->getModel()
            ->getLinksStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    protected function actionTops()
    {
        return $this->getModel()
            ->getTopsStats($this->getHostId());
    }

    /**
     * Возвращает модель для работы с с API Яндекс.Вебмастер
     * @throws InvalidArgumentException
     * @return YandexModel
     */
    protected function getModel()
    {
        $component = $this->getComponent();
        $oauthToken = $component->getSetting(YandexModel::YANDEX_OAUTH_TOKEN);
        if (is_null($oauthToken)) {
            throw new InvalidArgumentException($this->translate(
                "Option {option} is required",
                ['option' => YandexModel::YANDEX_OAUTH_TOKEN]
            ));
        }

        return new YandexModel($oauthToken);
    }

    /**
     * Возвращает идентификатор хоста по умолчанию.
     * @throws InvalidArgumentException
     * @return int
     */
    protected function getHostId()
    {
        if (is_null($this->hostId)) {
            $component = $this->getComponent();
            $hostId = $component->getSetting(YandexModel::YANDEX_HOST_ID);
            if (is_null($hostId)) {
                throw new InvalidArgumentException($this->translate(
                    "Option {option} is required",
                    ['option' => YandexModel::YANDEX_HOST_ID]
                ));
            }
            $this->hostId = $hostId;
        }

        return $this->hostId;
    }
}
