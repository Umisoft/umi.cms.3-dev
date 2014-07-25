<?php

return [
    'currentMode' => 'production',
    'corePath' => dirname(__DIR__) . '/umicms.phar',

    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__)
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,
        'browserCacheEnabled' => true,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__)
    ]
];