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
use umicms\project\admin\rest\component\DefaultQueryAdminComponent;
use umicms\project\admin\rest\controller\DefaultRestActionController;
use umicms\project\admin\component\AdminComponent;
use umicms\project\module\seo\model\YandexModel;

/**
 * Контроллер операций с API Яндекс.Вебмастер.
 */
class ActionController extends DefaultRestActionController
{
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
    public function actionHosts()
    {
        return $this->getModel()
            ->getHosts();
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    public function actionHost()
    {
        return $this->getModel()
            ->getHostStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    public function actionIndexed()
    {
        return $this->getModel()
            ->getIndexedStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    public function actionLinks()
    {
        return $this->getModel()
            ->getLinksStats($this->getHostId());
    }

    /**
     * Возвращает общую статистику по сайту
     * @return array
     */
    public function actionTops()
    {
        return $this->getModel()
            ->getTopsStats($this->getHostId());
    }

    /**
     * @return YandexModel
     * @throws InvalidArgumentException
     */
    protected function getModel()
    {
        /** @var $component AdminComponent */
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
            /** @var $component AdminComponent */
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

    /**
     * @return DefaultQueryAdminComponent
     */
    protected function getComponent()
    {
        return $this->getContext()->getComponent();
    }
}
