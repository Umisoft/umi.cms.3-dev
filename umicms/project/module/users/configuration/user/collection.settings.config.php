<?php

use umicms\project\module\users\api\collection\UserCollection;

return [
    UserCollection::SETTING_REGISTERED_USERS_DEFAULT_GROUP_GUIDS => [
        'bedcbbac-7dd1-4b60-979a-f7d944ecb08a' => 'bedcbbac-7dd1-4b60-979a-f7d944ecb08a',
        'daabebf8-f3b3-4f62-a23d-522eff9b7f68' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68'
    ],
    UserCollection::SETTING_REGISTRATION_WITH_ACTIVATION => 'true',

    UserCollection::SETTING_MAIL_SENDER => 'Administrator <admin@umisoft.ru>',

    UserCollection::SETTING_MAIL_NOTIFICATION_RECIPIENTS => 'Даша <dasha@umisoft.ru>, guzhova@umisoft.ru'
];