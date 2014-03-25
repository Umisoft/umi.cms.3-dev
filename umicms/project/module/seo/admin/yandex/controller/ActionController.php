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
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\component\SecureAdminComponent;
use umicms\project\module\seo\model\YandexModel;

/**
 * Контроллер операций с API Мегаиндекса
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var array $options
     */
    public $options;
    protected $hostId;

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['hosts', 'host', 'indexed', 'links', 'tops'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

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
        /** @var $component SecureAdminComponent */
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
     * @throws InvalidArgumentException
     * @return int
     */
    public function getHostId()
    {
        if (is_null($this->hostId)) {
            /** @var $component SecureAdminComponent */
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
}
