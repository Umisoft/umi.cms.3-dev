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
            'project.admin.rest.settings.users' => 'project.admin.rest.settings.users'
        ]
    ],

    'elements' => [
        UsersModule::SETTING_AUTH_COOKIE_TTL => [
            'type' => Text::TYPE_NAME,
            'label' => UsersModule::SETTING_AUTH_COOKIE_TTL,
            'options' => [
                'dataSource' => 'options.' . UsersModule::SETTING_AUTH_COOKIE_TTL
            ]
        ],
    ]

];