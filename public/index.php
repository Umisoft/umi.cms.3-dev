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
use umi\hmvc\exception\http\HttpException;
use umicms\project\Bootstrap;
use umicms\project\Environment;

require dirname(__DIR__) . '/configuration/core.php';


try {
    $bootstrap = new Bootstrap();
    if ($bootstrap->init()) {
        if (Environment::$toolkitInitializer) {
            /** @var callable $initializer */
            $initializer = require Environment::$toolkitInitializer;
            $initializer($bootstrap->getToolkit());
        }
        $bootstrap->dispatch();
    }
    $bootstrap->getResponse()->send();
} catch (HttpException $e) {
    Environment::reportCoreError('exception.phtml', ['e' => $e], $e->getCode());
} catch (\Exception $e) {
    Environment::reportCoreError('exception.phtml', ['e' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
}
