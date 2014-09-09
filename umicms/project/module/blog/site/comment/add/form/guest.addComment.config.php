<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Hidden;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\model\object\GuestBlogComment;

return [
    'options' => [
        'dictionaries' => [
            'collection.blogComment', 'collection', 'form'
        ]
    ],
    'attributes' => [
        'method' => 'post'
    ],
    'elements' => [
        GuestBlogComment::FIELD_POST => [
            'type' => Hidden::TYPE_NAME,
            'label' => GuestBlogComment::FIELD_POST,
            'options' => [
                'dataSource' => GuestBlogComment::FIELD_POST
            ],
        ],
        GuestBlogComment::FIELD_AUTHOR => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogComment::FIELD_AUTHOR,
            'options' => [
                'dataSource' => GuestBlogComment::FIELD_AUTHOR
            ],
        ],
        GuestBlogComment::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => GuestBlogComment::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => GuestBlogComment::FIELD_DISPLAY_NAME
            ],
        ],
        GuestBlogComment::FIELD_CONTENTS => [
            'type' => Textarea::TYPE_NAME,
            'label' => GuestBlogComment::FIELD_CONTENTS,
            'options' => [
                'dataSource' => GuestBlogComment::FIELD_CONTENTS
            ]
        ],
        BaseFormWidget::INPUT_REDIRECT_URL => [
            'type' => Hidden::TYPE_NAME
        ],
        'submit' => [
            'type' => Submit::TYPE_NAME,
            'label' => 'Add'
        ]
    ]
];