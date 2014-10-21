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
use umicms\project\module\dispatches\model\DispatchModule;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.dispatches' => 'project.admin.rest.settings.dispatches'
        ]
    ],

    'elements' => [
        DispatchModule::SETTING_MAIL_SENDER => [
            'type' => Text::TYPE_NAME,
            'label' => DispatchModule::SETTING_MAIL_SENDER,
            'options' => [
                'dataSource' => 'options.' . DispatchModule::SETTING_MAIL_SENDER
            ]
        ],
        DispatchModule::SETTING_COUNT_SEND => [
            'type' => Text::TYPE_NAME,
            'label' => DispatchModule::SETTING_COUNT_SEND,
            'options' => [
                'dataSource' => 'options.' . DispatchModule::SETTING_COUNT_SEND
            ]
        ],
    ]
];