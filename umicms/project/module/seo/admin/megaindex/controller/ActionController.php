<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\seo\admin\megaindex\controller;

use umicms\project\admin\api\controller\BaseRestActionController;
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
     * @param MegaindexModel $model
     */
    public function __construct(MegaindexModel $model)
    {
        $this->model = $model;
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
    public function actionMethod()
    {
        $method = $this->getRequiredQueryVar('method');
        $vars = $this->getAllQueryVars();
        unset($vars['method']);
        return $this->model->queryApi($method, $vars);
    }
}
