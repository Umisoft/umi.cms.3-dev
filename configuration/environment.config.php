<?php

return [
    'currentMode' => 'development',
    'corePath' => dirname(__DIR__) . '/umicms.phar',

    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),
        
        'cacheTemplateEnabled' => false
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,
        'browserCacheEnabled' => true,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => true
    ]
];