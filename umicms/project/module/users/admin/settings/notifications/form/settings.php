<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Text;
use umicms\project\module\users\model\UsersModule;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.users'
        ]
    ],

    'elements' => [
        UsersModule::SETTING_MAIL_SENDER => [
            'type' => Text::TYPE_NAME,
            'label' => UsersModule::SETTING_MAIL_SENDER,
            'options' => [
                'dataSource' => 'options.' . UsersModule::SETTING_MAIL_SENDER
            ]
        ],
        UsersModule::SETTING_MAIL_NOTIFICATION_RECIPIENTS => [
            'type' => Text::TYPE_NAME,
            'label' => UsersModule::SETTING_MAIL_NOTIFICATION_RECIPIENTS,
            'options' => [
                'dataSource' => 'options.' . UsersModule::SETTING_MAIL_NOTIFICATION_RECIPIENTS
            ]
        ]
    ]
];