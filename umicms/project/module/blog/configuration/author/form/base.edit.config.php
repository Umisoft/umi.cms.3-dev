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
use umi\form\fieldset\FieldSet;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\BlogAuthor;

return [

    'options' => [
        'dictionaries' => [
            'collection.blogAuthor', 'collection', 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BlogAuthor::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                BlogAuthor::FIELD_PROFILE => [
                    'type' => Select::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PROFILE,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => BlogAuthor::FIELD_PROFILE
                    ],
                ],
                BlogAuthor::FIELD_PAGE_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogAuthor::FIELD_PAGE_CONTENTS,
                    'options' => [
                        'dataSource' => BlogAuthor::FIELD_PAGE_CONTENTS
                    ]
                ]
            ],

        ]
    ]
];