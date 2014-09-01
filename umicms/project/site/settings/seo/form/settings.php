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
use umicms\project\IProjectSettingsAware;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION
            ]
        ],
        IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS
            ]
        ],
        IProjectSettingsAware::SETTING_DEFAULT_TITLE => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_TITLE,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_TITLE
            ]
        ],
        IProjectSettingsAware::SETTING_TITLE_PREFIX => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_TITLE_PREFIX,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_TITLE_PREFIX
            ]
        ]
    ]

];