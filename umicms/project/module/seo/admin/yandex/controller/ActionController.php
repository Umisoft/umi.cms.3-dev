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
use umicms\project\admin\component\AdminComponent;
use umicms\project\module\seo\model\YandexModel;

/**
 * Контроллер операций с API Мегаиндекса
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var int $hostId
     */
    public $hostId;
    /**
     * @var YandexModel $model
     */
    private $model;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        /** @var $component AdminComponent */
        $component = $this->getComponent();
        $options = $component->getSettings()['oauthToken'];
        if (!isset($options['oauthToken'])) {
            throw new InvalidArgumentException(
                $this->translate("Option {option} is required", ['option'=>'oauthToken'])
            );
        }
        $this->model = new YandexModel($options['oauthToken']);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['method'];
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        return [];
    }

    /**
     * Вызывает метод API Мегаиндекса
     * @return array
     */
    public function actionStats()
    {
        return $this->model->getSiteStats($this->hostId);
    }
}
