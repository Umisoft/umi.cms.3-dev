<?php

return [
    'currentMode' => 'development',


    'development' => [
        'errorReporting' => E_ALL,
        'displayErrors' => true,
        'showExceptionTrace' => true,
        'showExceptionStack' => true,

        'directoryProjects' => dirname(__DIR__),
    ],
    'production' => [
        'errorReporting' => 0,
        'displayErrors' => false,
        'showExceptionTrace' => false,
        'showExceptionStack' => false,

        'directoryProjects' => dirname(__DIR__),
    ]
];