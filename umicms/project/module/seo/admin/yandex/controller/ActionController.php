<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\seo\admin\yandex\controller;

use umicms\exception\InvalidArgumentException;
use umicms\project\admin\api\component\DefaultQueryAdminComponent;
use umicms\project\admin\api\controller\DefaultRestActionController;
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
        $options = $component->getSettings()['options'];
        if (!isset($options['oauthToken'])) {
            throw new InvalidArgumentException(
                $this->translate("Option {option} is required", ['option' => 'oauthToken'])
            );
        }

        return new YandexModel($options['oauthToken']);
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
            $options = $component->getSettings()['options'];
            if (!isset($options['hostId'])) {
                throw new InvalidArgumentException(
                    $this->translate("Option {option} is required", ['option' => 'hostId'])
                );
            }
            $this->hostId = $options['hostId'];
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
