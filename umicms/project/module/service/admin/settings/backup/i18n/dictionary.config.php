<?php

use umicms\project\module\service\model\collection\BackupCollection;

return [
    'en-US' => [
        'component:backup:displayName' => 'Backups',
        BackupCollection::SETTING_OBJECT_HISTORY_SIZE => 'Maximum number of stored object backups',

        'role:configurator:displayName' => 'Manage settings'
    ],

    'ru-RU' => [
        'component:backup:displayName' => 'Резервные копии',
        BackupCollection::SETTING_OBJECT_HISTORY_SIZE => 'Количество резервных копий объекта',

        'role:configurator:displayName' => 'Управление настройками'
    ]
];
 