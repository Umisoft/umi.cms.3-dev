<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\controller;

use umicms\base\controller\BaseController;

/**
 * Основной контроллер административной панели.
 */
class DefaultController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createResponse('здесь будет админка');
    }

}


