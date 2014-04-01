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
use umicms\project\admin\api\controller\BaseRestActionController;
use umicms\project\admin\component\AdminComponent;
use umicms\project\module\seo\model\MegaindexModel;

/**
 * Контроллер операций с API Мегаиндекса
 */
class ActionController extends BaseRestActionController
{
    /**
     * @var MegaindexModel $model
     */
    private $model;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        /** @var $component AdminComponent */
        $component = $this->getComponent();
        $options = $component->getSettings()['options'];
        if (!isset($options['login'])) {
            throw new InvalidArgumentException($this->translate("Option {option} is required", ['option'=>'login']));
        }
        if (!isset($options['password'])) {
            throw new InvalidArgumentException($this->translate("Option {option} is required", ['option'=>'password']));
        }
        if (!isset($options['siteUrl'])) {
            throw new InvalidArgumentException($this->translate("Option {option} is required", ['option'=>'siteUrl']));
        }
        $this->model = new MegaindexModel($options['login'], $options['password'], $options['siteUrl']);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        return ['siteAnalyse', 'scanYandex'];
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
    public function actionMethod()
    {
        $vars = $this->getAllQueryVars();
        return $this->model->queryApi($this->getRequiredQueryVar('method'), $vars);
    }

    public function actionSiteAnalyse()
    {
        return $this->model->queryApi('siteAnalyse', []);
    }

    public function actionScanYandex()
    {
        return $this->model->queryApi('scan_yandex_position', []);
    }
}
