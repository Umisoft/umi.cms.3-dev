<?php
/**
 * Front-controller для UMI.CMS. Обрабатывает HTTP-запросы.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\http\Response;
use umicms\project\Bootstrap;
use umicms\project\Environment;

require dirname(__DIR__) . '/configuration/core.php';


try {
    $bootstrap = new Bootstrap();
    $bootstrap->init();
    if (Environment::$toolkitInitializer) {
        require Environment::$toolkitInitializer;
        toolkit_initializer($bootstrap->getToolkit());
    }
    $bootstrap->dispatch();
    $bootstrap->sendResponse();
} catch (\Exception $e) {

    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    if ($e instanceof HttpException) {
        $code = $e->getCode();
    }
    Environment::reportCoreError('exception.phtml', ['e' => $e], $code);
}
