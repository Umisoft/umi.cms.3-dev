<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\project\module\dispatches\model\object\BaseSubscriber;
use umicms\project\module\dispatches\model\object\RegisteredSubscriber;

return [
    'en-US' => [
        BaseSubscriber::FIELD_EMAIL                   => 'E-mail',
        BaseSubscriber::FIELD_FIRST_NAME              => 'First name',
        BaseSubscriber::FIELD_LAST_NAME               => 'Last name',
        BaseSubscriber::FIELD_MIDDLE_NAME             => 'Middle name',
        BaseSubscriber::FIELD_DISPATCHES              => 'Dispatches',
        BaseSubscriber::FIELD_UNSUBSCRIBED_DISPATCHES => 'Unsubscribed dispatches',
        RegisteredSubscriber::FIELD_PROFILE           => 'User profile',
        'additional'                                  => 'Additional',
    ],
    'ru-RU' => [
        BaseSubscriber::FIELD_EMAIL                   => 'E-mail',
        BaseSubscriber::FIELD_FIRST_NAME              => 'Имя',
        BaseSubscriber::FIELD_LAST_NAME               => 'Фамилия',
        BaseSubscriber::FIELD_MIDDLE_NAME             => 'Отчество',
        BaseSubscriber::FIELD_DISPATCHES              => 'Рассылки',
        BaseSubscriber::FIELD_UNSUBSCRIBED_DISPATCHES => 'Отписанные рассылки',
        RegisteredSubscriber::FIELD_PROFILE           => 'Профиль пользователя',
        'additional'                                  => 'Дополнительно',
    ]
];
 