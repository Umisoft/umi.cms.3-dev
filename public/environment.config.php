<?php

return [
    'currentMode' => 'development',
    'corePath' => dirname(__DIR__) . '/umicms.phar',
    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'directoryPublic' => __DIR__
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'directoryPublic' => __DIR__
    ]
];