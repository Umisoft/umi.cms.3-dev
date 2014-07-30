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
use umicms\project\site\SiteApplication;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        SiteApplication::SETTING_DEFAULT_PAGE_GUID => [
            'type' => SingleCollectionObjectRelation::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_PAGE_GUID,
            'options' => [
                'collection' => 'structure',
                'dataSource' => SiteApplication::SETTING_DEFAULT_PAGE_GUID
            ]
        ],
        SiteApplication::SETTING_DEFAULT_LAYOUT_GUID => [
            'type' => SingleCollectionObjectRelation::TYPE_NAME,
            'label' => SiteApplication::SETTING_DEFAULT_LAYOUT_GUID,
            'options' => [
                'collection' => 'layout',
                'dataSource' => SiteApplication::SETTING_DEFAULT_LAYOUT_GUID
            ]
        ]
    ]

];