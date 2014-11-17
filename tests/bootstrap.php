<?php

$environmentMode = 'console';
require dirname(__DIR__) . '/configuration/core.php';

$srcPath = dirname(__DIR__);

/*

TODO: include Aspect Mock
$kernel = Kernel::getInstance();

$kernel->init(
    [
        'debug'         => true,
        'cacheDir'      => $srcPath . '/cache/mock',
        'prebuiltCache' => true,
        'includePaths'  => [$srcPath . '/umicms'],
        'excludePaths' => [$srcPath]
    ]
);
*/

require __DIR__ . '/common/UrlMap.php';
require __DIR__ . '/common/UmiModule.php';
require __DIR__ . '/common/UmiConnector.php';