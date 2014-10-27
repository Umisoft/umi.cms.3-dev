<?php

return [
    'defaultMode' => 'production',

    'development' => [
        'corePath' => dirname(__DIR__) . '/umicms/bootstrap.php',

        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => null,
    ],

    'test' => [
        'corePath' => dirname(__DIR__) . '/umicms/bootstrap.php',

        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => dirname(__DIR__) . '/configuration/env.init.test.php',
    ],

    'production' => [
        'corePath' => dirname(__DIR__) . '/umicms.phar',

        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,
        'browserCacheEnabled' => true,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => null,
    ],

    'console' => [
        'corePath' => dirname(__DIR__) . '/umicms/bootstrap.php',

        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,
        'browserCacheEnabled' => false,

        'timezone' => '%timezone%',

        'directoryPublic' => dirname(__DIR__) . '/public',
        'directoryRoot' => dirname(__DIR__),

        'cacheTemplateEnabled' => false,

        'toolkitInitializer' => null,
    ]
];