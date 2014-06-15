<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\controller;

use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\dispatcher\CmsDispatcher;

/**
 * Контроллер для REST-вызова виджета.
 */
class SiteRestWidgetController extends BaseCmsController
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
         * @var CmsDispatcher $dispatcher
         */
        $dispatcher = $this->getContext()->getDispatcher();

        $result = $dispatcher->executeWidgetByPath($widgetPath, $this->getAllQueryVars());

        $response = $this->createResponse($result);

        return $response;
    }
}
 