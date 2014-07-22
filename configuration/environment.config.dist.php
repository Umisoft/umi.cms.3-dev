<?php

return [
    'currentMode' => 'production',
    'corePath' => dirname(__DIR__) . '/umicms.phar',
    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ]
];