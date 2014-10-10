<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\form\element\SingleCollectionObjectRelation;
use umicms\project\IProjectSettingsAware;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site' => 'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        IProjectSettingsAware::SETTING_DEFAULT_PAGE_GUID => [
            'type' => SingleCollectionObjectRelation::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_PAGE_GUID,
            'options' => [
                'collection' => 'structure',
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_PAGE_GUID
            ]
        ],
        IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID => [
            'type' => SingleCollectionObjectRelation::TYPE_NAME,
            'label' => IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID,
            'options' => [
                'collection' => 'layout',
                'dataSource' => IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID
            ]
        ]
    ]

];