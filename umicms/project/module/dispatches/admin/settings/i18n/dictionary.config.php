<?php

use umicms\project\module\dispatches\model\DispatchModule;

return [
    'en-US' => [
        'component:dispatches:displayName' => 'Dispatches',
        'role:dispatchesExecutor:displayName' => 'Settings dispatches',
        DispatchModule::SETTING_MAIL_SENDER => 'Sender e-mail dispatches',
        DispatchModule::SETTING_COUNT_SEND => 'The number of emails sent per iteration',
    ],

    'ru-RU' => [
        'component:dispatches:displayName' => 'Рассылки',
        DispatchModule::SETTING_MAIL_SENDER => 'E-mail отправителя рассылок',
        DispatchModule::SETTING_COUNT_SEND => 'Количество писем отправляемое за одну итерацию',

        'role:dispatchesExecutor:displayName' => 'Настройка рассылки',

    ]
];