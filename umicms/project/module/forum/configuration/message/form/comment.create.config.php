<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\html5\DateTime;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umi\validation\IValidatorFactory;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\forum\model\object\ForumMessage;

return [

    'options' => [
        'dictionaries' => [
            'collection.forumMessage' => 'collection.forumMessage', 'collection' => 'collection', 'form' => 'form'
        ]
    ],
    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                ForumMessage::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => ForumMessage::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => ForumMessage::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ],
        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'contents',
            'elements' => [
                ForumMessage::FIELD_THEME => [
                    'type' => Select::TYPE_NAME,
                    'label' => ForumMessage::FIELD_THEME,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => ForumMessage::FIELD_THEME
                    ]
                ],
                ForumMessage::FIELD_AUTHOR => [
                    'type' => Select::TYPE_NAME,
                    'label' => ForumMessage::FIELD_AUTHOR,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => ForumMessage::FIELD_AUTHOR,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ]
                    ]
                ],
                ForumMessage::FIELD_PUBLISH_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => BlogComment::FIELD_PUBLISH_TIME,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_PUBLISH_TIME
                    ]
                ],
                ForumMessage::FIELD_CONTENTS => [
                    'type' => Wysiwyg::TYPE_NAME,
                    'label' => BlogComment::FIELD_CONTENTS,
                    'options' => [
                        'dataSource' => BlogComment::FIELD_CONTENTS
                    ]
                ]
            ],

        ]
    ]
];
