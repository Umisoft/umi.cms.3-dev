<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\RegisteredSubscriber;

return [
    'en-US' => [
        Subscriber::FIELD_EMAIL                   => 'E-mail',
        Subscriber::FIELD_FIRST_NAME              => 'First name',
        Subscriber::FIELD_LAST_NAME               => 'Last name',
        Subscriber::FIELD_MIDDLE_NAME             => 'Middle name',
        Subscriber::FIELD_DISPATCHES              => 'Dispatches',
        Subscriber::FIELD_UNSUBSCRIBED_DISPATCHES => 'Unsubscribed dispatches',
        //RegisteredSubscriber::FIELD_PROFILE           => 'User profile',
        'additional'                                  => 'Additional',
    ],
    'ru-RU' => [
        Subscriber::FIELD_EMAIL                   => 'E-mail',
        Subscriber::FIELD_FIRST_NAME              => 'Имя',
        Subscriber::FIELD_LAST_NAME               => 'Фамилия',
        Subscriber::FIELD_MIDDLE_NAME             => 'Отчество',
        Subscriber::FIELD_DISPATCHES              => 'Рассылки',
        Subscriber::FIELD_UNSUBSCRIBED_DISPATCHES => 'Отписанные рассылки',
        //RegisteredSubscriber::FIELD_PROFILE           => 'Профиль пользователя',
        'additional'                                  => 'Дополнительно',
    ]
];
 