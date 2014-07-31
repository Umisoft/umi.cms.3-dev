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
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umicms\form\element\File;
use umicms\form\element\Image;
use umicms\form\element\Wysiwyg;

return [
    'elements' => [
        'contents' => [
            'elements' => [
                'image' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'image',
                    'options' => [
                        'dataSource' => 'image'
                    ]
                ],
                'announcementImage' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'announcementImage',
                    'options' => [
                        'dataSource' => 'announcementImage'
                    ]
                ]
            ]
        ],
        'additional' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'additional',
            'elements' => [
                'popular' => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => 'popular',
                    'options' => [
                        'dataSource' => 'popular'
                    ],
                ],
                'firstImage' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'firstImage',
                    'options' => [
                        'dataSource' => 'firstImage'
                    ]
                ],
                'secondImage' => [
                    'type' => Image::TYPE_NAME,
                    'label' => 'secondImage',
                    'options' => [
                        'dataSource' => 'secondImage'
                    ]
                ],
                'file' => [
                    'type' => File::TYPE_NAME,
                    'label' => 'file',
                    'options' => [
                        'dataSource' => 'file'
                    ]
                ],
                'secondContents' => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => 'secondContents',
                    'options' => [
                        'dataSource' => 'secondContents'
                    ]
                ],
                'simpleText' => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => 'simpleText',
                    'options' => [
                        'dataSource' => 'simpleText'
                    ]
                ]
            ]
        ]
    ]
];