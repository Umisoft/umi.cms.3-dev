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
use umi\form\fieldset\FieldSet;
use umicms\form\element\PageRelation;
use umicms\project\module\structure\model\object\MenuInternalItem;

return [

    'options' => [
        'dictionaries' => [
            'collection.menu', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                MenuInternalItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => MenuInternalItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => MenuInternalItem::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],

        'settings' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'settings',
            'elements' => [
                MenuInternalItem::FIELD_PAGE_RELATION => [
                    'type' => PageRelation::TYPE_NAME,
                    'label' => MenuInternalItem::FIELD_PAGE_RELATION,
                    'options' => [
                        'dataSource' => MenuInternalItem::FIELD_PAGE_RELATION
                    ],
                ]
            ]
        ]
    ]
];