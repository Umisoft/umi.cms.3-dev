<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\http\Response;
use umicms\project\Bootstrap;
use umicms\project\Environment;

error_reporting(-1);
ini_set('display_errors', 1);


require __DIR__ . '/umicms/bootstrap.php';

$envConfigFile = __DIR__ . '/configuration/environment.config.php';
if (is_file($envConfigFile)) {
    /** @noinspection PhpIncludeInspection */
    Environment::init(require $envConfigFile);
}

try {
    (new Bootstrap())->run();
} catch (\Exception $e) {

    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    if ($e instanceof HttpException) {
        $code = $e->getCode();
    }
    Environment::reportCoreError('exception.phtml', ['e' => $e], $code);
}
