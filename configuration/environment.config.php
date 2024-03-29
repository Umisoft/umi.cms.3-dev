<?php

return [
    'defaultMode' => 'development',

    'development' => [
        'corePath' => dirname(__DIR__) . '/umicms/bootstrap.php',

        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),
        
        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => null,
    ],

    'production' => [
        'corePath' => dirname(__DIR__) . '/umicms.phar',

        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,
        'browserCacheEnabled' => true,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => true,

        'toolkitInitializer' => null,
    ],

    'console' => [
        'corePath' => dirname(__DIR__) . '/umicms/bootstrap.php',

        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => 'UTC',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => null,
    ]
];