<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Select;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\project\IProjectSettingsAware;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site' => 'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => [
            'type' => Select::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE,
            'options' => [
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE,
                'choices' => [
                    'php' => 'php',
                    'twig' => 'twig',
                    'xslt' => 'xslt'
                ]
            ]
        ],
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION
            ]
        ],
        IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY
            ]
        ],
        IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY => [
            'type' => Text::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY,
            'options' => [
                'dataSource' => IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY
            ]
        ]
    ]
];