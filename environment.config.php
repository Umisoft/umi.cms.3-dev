<?php

return [
    'currentMode' => 'development',

    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'directoryProjects' => __DIR__ . '/project',
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'directoryProjects' => __DIR__ . '/project',
    ]
];