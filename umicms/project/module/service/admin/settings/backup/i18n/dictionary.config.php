<?php

use umicms\project\module\service\model\collection\BackupCollection;

return [
    'en-US' => [
        'component:backup:displayName' => 'Backups',
        BackupCollection::SETTING_OBJECT_HISTORY_SIZE => 'Maximum number of stored object backups'
    ],

    'ru-RU' => [
        'component:backup:displayName' => 'Резервирование',
        BackupCollection::SETTING_OBJECT_HISTORY_SIZE => 'Максимальное количество хранимых для объекта резервных копий'
    ]
];
 