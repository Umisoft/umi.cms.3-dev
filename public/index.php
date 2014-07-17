<?php
/**
 * Front-controller для UMI.CMS. Обрабатывает HTTP-запросы.
 *
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\http\Response;
use umicms\project\Bootstrap;
use umicms\project\Environment;

require __DIR__ . '/core.php';


try {
    (new Bootstrap())->run();
} catch (\Exception $e) {

    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    if ($e instanceof HttpException) {
        $code = $e->getCode();
    }
    Environment::reportCoreError('exception.phtml', ['e' => $e], $code);
}
