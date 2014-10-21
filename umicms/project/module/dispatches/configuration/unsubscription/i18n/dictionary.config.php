<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\dispatches\model\object\Unsubscription;

return [
    'en-US' => [
        Unsubscription::FIELD_RELEASE    => 'Release',
        Unsubscription::FIELD_SUBSCRIBER => 'Subscriber',
        Unsubscription::FIELD_REASON     => 'Unsubscribe reasons',

        'component:unsubscription:displayName' => 'Unsubscribe reasons'
    ],
    'ru-RU' => [
        Unsubscription::FIELD_RELEASE    => 'Выпуск рассылки',
        Unsubscription::FIELD_SUBSCRIBER => 'Подписчик',
        Unsubscription::FIELD_REASON     => 'Причина отписки',

        'component:unsubscription:displayName' => 'Причины отписки'
    ]
];