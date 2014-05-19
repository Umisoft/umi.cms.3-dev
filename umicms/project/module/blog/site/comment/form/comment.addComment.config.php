<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Hidden;
use umi\form\element\Submit;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\blog\api\object\BlogComment;

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
        BlogComment::FIELD_POST => [
            'type' => Hidden::TYPE_NAME,
            'label' => BlogComment::FIELD_POST,
            'options' => [
                'dataSource' => BlogComment::FIELD_POST
            ],
        ],
        BlogComment::FIELD_DISPLAY_NAME => [
            'type' => Text::TYPE_NAME,
            'label' => BlogComment::FIELD_DISPLAY_NAME,
            'options' => [
                'dataSource' => BlogComment::FIELD_DISPLAY_NAME
            ],
        ],
        BlogComment::FIELD_CONTENTS => [
            'type' => Textarea::TYPE_NAME,
            'label' => BlogComment::FIELD_CONTENTS,
            'options' => [
                'dataSource' => BlogComment::FIELD_CONTENTS
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