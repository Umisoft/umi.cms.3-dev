<?php

return [
    'currentMode' => 'production',
    'corePath' => dirname(__DIR__) . '/umicms.phar',
    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryConfiguration' => dirname(__DIR__) . '/configuration'
    ]
];