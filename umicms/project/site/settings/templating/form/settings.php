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
use umicms\project\site\SiteApplication;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        SiteApplication::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => [
            'type' => Select::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE,
            'options' => [
                'dataSource' => SiteApplication::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE,
                'choices' => [
                    'php' => 'php',
                    'twig' => 'twig',
                    'xslt' => 'xslt'
                ]
            ]
        ],
        SiteApplication::SETTING_DEFAULT_TEMPLATE_EXTENSION => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_TEMPLATE_EXTENSION,
            'options' => [
                'dataSource' => SiteApplication::SETTING_DEFAULT_TEMPLATE_EXTENSION
            ]
        ],
        SiteApplication::SETTING_TEMPLATE_DIRECTORY => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_TEMPLATE_DIRECTORY,
            'options' => [
                'dataSource' => SiteApplication::SETTING_TEMPLATE_DIRECTORY
            ]
        ],
        SiteApplication::SETTING_COMMON_TEMPLATE_DIRECTORY => [
            'type' => Text::TYPE_NAME,
            'label' => SiteApplication::SETTING_COMMON_TEMPLATE_DIRECTORY,
            'options' => [
                'dataSource' => SiteApplication::SETTING_COMMON_TEMPLATE_DIRECTORY
            ]
        ]
    ]
];