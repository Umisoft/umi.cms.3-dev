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
use umi\form\element\MultiSelect;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\validation\IValidatorFactory;
use umicms\form\element\Image;
use umicms\form\element\Wysiwyg;
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.create.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.blogPost' => 'collection.blogPost'
            ]
        ],
        'elements' => [
            'contents' => [
                'elements' => [
                    BlogPost::FIELD_CATEGORY => [
                        'type' => Select::TYPE_NAME,
                        'label' => BlogPost::FIELD_CATEGORY,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => BlogPost::FIELD_CATEGORY
                        ]
                    ],
                    BlogPost::FIELD_TAGS => [
                        'type' => MultiSelect::TYPE_NAME,
                        'label' => BlogPost::FIELD_TAGS,
                        'options' => [
                            'dataSource' => BlogPost::FIELD_TAGS,
                            'lazy' => true
                        ]
                    ],
                    BlogPost::FIELD_AUTHOR => [
                        'type' => Select::TYPE_NAME,
                        'label' => BlogPost::FIELD_AUTHOR,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => BlogPost::FIELD_AUTHOR,
                            'validators' => [
                                IValidatorFactory::TYPE_REQUIRED => []
                            ]
                        ],
                    ],
                    BlogPost::FIELD_PUBLISH_TIME => [
                        'type' => DateTime::TYPE_NAME,
                        'label' => BlogPost::FIELD_PUBLISH_TIME,
                        'options' => [
                            'dataSource' => BlogPost::FIELD_PUBLISH_TIME
                        ]
                    ],
                    BlogPost::FIELD_STATUS => [
                        'type' => Select::TYPE_NAME,
                        'label' => BlogPost::FIELD_STATUS,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => BlogPost::FIELD_STATUS,
                            'validators'    => [
                                IValidatorFactory::TYPE_REQUIRED => []
                            ],
                        ]
                    ],
                    BlogPost::FIELD_ANNOUNCEMENT => [
                        'type' => Wysiwyg::TYPE_NAME,
                        'label' => BlogPost::FIELD_ANNOUNCEMENT,
                        'options' => [
                            'dataSource' => BlogPost::FIELD_ANNOUNCEMENT
                        ]
                    ],
                    BlogPost::FIELD_IMAGE => [
                        'type' => Image::TYPE_NAME,
                        'label' => BlogPost::FIELD_IMAGE,
                        'options' => [
                            'dataSource' => BlogPost::FIELD_IMAGE
                        ]
                    ],
                    BlogPost::FIELD_SOURCE => [
                        'type' => Text::TYPE_NAME,
                        'label' => BlogPost::FIELD_SOURCE,
                        'options' => [
                            'dataSource' => BlogPost::FIELD_SOURCE
                        ]
                    ]
                ]
            ]
        ]
    ]
);