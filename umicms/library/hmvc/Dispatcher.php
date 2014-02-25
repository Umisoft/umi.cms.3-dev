<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc;

use umi\hmvc\dispatcher\Dispatcher as FrameworkDispatcher;
use umi\http\Request;
use umi\http\Response;

/**
 * {@inheritdoc}
 */
class Dispatcher extends FrameworkDispatcher
{
    /**
     * {@inheritdoc}
     */
    protected function sendResponse(Response $response, Request $request, $content)
    {
        $this->setUmiHeaders($response);

        $response->setETag(md5($content));
        $response->setPublic();
        $response->isNotModified($request);

        parent::sendResponse($response, $request, $content);
    }

    /**
     * Выставляет заголовки UMI.CMS.
     * @param Response $response
     */
    protected function setUmiHeaders(Response $response)
    {
        global $umicmsStartTime;

        $response->headers->set('X-Generated-By', 'UMI.CMS');
        if ($umicmsStartTime > 0) {
            $response->headers->set('X-Generation-Time', round(microtime(true) - $umicmsStartTime, 3));
        }
    }
}
 