<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rubric\controller;

use umicms\project\admin\controller\BaseRestActionController;
use umicms\project\module\news\api\NewsPublicApi;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{

    /**
     * @var NewsPublicApi $api
     */
    protected $api;

    /**
     * Конструктор.
     * @param NewsPublicApi $api
     */
    public function __construct(NewsPublicApi $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    protected function getQueryActions()
    {
        return ['settings', 'form'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModifyActions()
    {
        return [];
    }

    /**
     * Возвращает форму.
     */
    protected function actionForm()
    {
        // TODO: add form
        return $this->api->rubric()->getCollection()->getMetadata()->getBaseType();
    }


}
 