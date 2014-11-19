<?php

namespace umitest;

global $environmentMode;
$environmentMode= 'console';

require dirname(__DIR__) . '/configuration/core.php';
require __DIR__ . '/aspectMock.php';

require __DIR__ . '/common/UrlMap.php';
require __DIR__ . '/common/UmiModule.php';
require __DIR__ . '/common/UmiConnector.php';


$kernel = AopKernel::getInstance();

$srcPath = dirname(__DIR__);

$kernel->init(
    [
        'debug'         => true,
        'cacheDir'      => $srcPath . '/cache/mock',
        'prebuiltCache' => true,
        'includePaths'  => [$srcPath],
        'allowedNamespaces' => [
            'umicms\form\\',
            'umicms\project\module\\',
            'umicms\hmvc\\',
            'umi\messages\\'
        ]
    ]
);