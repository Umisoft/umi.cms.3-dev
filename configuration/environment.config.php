<?php

use umicms\project\Environment;

return [
    Environment::DEV_MODE => [
        Environment::ERROR_REPORTING => E_ALL,
        Environment::DISPLAY_ERRORS => 1,
        Environment::DISPLAY_EXCEPTION_STACK => true,
        Environment::DISPLAY_EXCEPTION_TRACE => true
    ],
    Environment::PRODUCTION_MODE => [
        Environment::ERROR_REPORTING => 0,
        Environment::DISPLAY_ERRORS => 0,
        Environment::DISPLAY_EXCEPTION_STACK => false,
        Environment::DISPLAY_EXCEPTION_TRACE => false
    ],
    Environment::CURRENT_MODE => Environment::DEV_MODE
];