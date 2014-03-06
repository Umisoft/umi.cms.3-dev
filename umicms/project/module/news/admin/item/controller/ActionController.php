<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\item\controller;

use umicms\project\admin\controller\BaseRestActionController;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class ActionController extends BaseRestActionController
{

    /**
     * {@inheritdoc}
     */
    protected function getQueryActions()
    {
        return ['settings'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModifyActions()
    {
        return [];
    }

}
 