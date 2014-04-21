<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umicms\hmvc\controller\BaseController;
use umicms\hmvc\dispatcher\Dispatcher;

/**
 * Контроллер для REST-вызова виджета.
 */
class SiteRestWidgetController extends BaseController
{
    /**
     * Имя контроллера.
     */
    const NAME = 'widget';

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $widgetPath = $this->getRouteVar('path');

        /**
         * @var Dispatcher $dispatcher
         */
        $dispatcher = $this->getContext()->getDispatcher();
        $result = $dispatcher->executeWidgetByPath($widgetPath, $this->getAllQueryVars());

        return $this->createResponse($result);
    }
}
 