<?php

return [
    'currentMode' => 'development',
    'corePath' => dirname(__DIR__) . '/umicms.phar',

    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ]
];