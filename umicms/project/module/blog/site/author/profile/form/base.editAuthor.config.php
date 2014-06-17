<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\CSRF;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umicms\project\module\blog\model\object\BlogAuthor;

return [
    'options' => [
        'dictionaries' => [
            'collection.blogAuthor', 'collection', 'form'
        ]
    ],
    'attributes' => [
        'method' => 'post'
    ],
    'elements' => [
        BlogAuthor::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => BlogAuthor::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => BlogAuthor::FIELD_DISPLAY_NAME
            ]
        ],
        BlogAuthor::FIELD_PAGE_CONTENTS => [
            'type' => Textarea::TYPE_NAME,
            'label' => BlogAuthor::FIELD_PAGE_CONTENTS,
            'options' => [
                'dataSource' => BlogAuthor::FIELD_PAGE_CONTENTS_RAW
            ]
        ],
        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Save'
        ]
    ]
];