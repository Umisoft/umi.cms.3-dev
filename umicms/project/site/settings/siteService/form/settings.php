<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\MultiSelect;
use umi\form\element\Textarea;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site' => 'project.admin.rest.settings.site'
        ]
    ],

    'elements' => [
        'isSiteService' => [
            'type' => Checkbox::TYPE_NAME,
            'label' => 'isSiteService',
            'options' => [
                'dataSource' => 'isSiteService'
            ]
        ],
        'textServiceSite' => [
            'type' => Textarea::TYPE_NAME,
            'label' => 'textServiceSite',
            'options' => [
                'dataSource' => 'textServiceSite'
            ]
        ],
        'allowedGroupsUser' => [
            'type' => MultiSelect::TYPE_NAME,
            'label' => 'allowedGroupsUser',
            'options' => [
                'collection' => 'userGroup',
                'dataSource' => 'allowedGroupsUser'
            ]
        ]
    ]
];