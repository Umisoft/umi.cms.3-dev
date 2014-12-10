<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\seo\model\object\Redirect;

return [

    'options' => [
        'dictionaries' => [
            'collection.redirects' => 'collection.redirects', 'collection' => 'collection', 'form' => 'form'
        ],
    ],

    'elements' => [

        'displayName' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'displayName',
            'elements' => [
                Redirect::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Redirect::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Redirect::FIELD_DISPLAY_NAME,
                    ],
                ],
            ],
        ],

        'patterns' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'patterns',
            'elements' => [
                Redirect::FIELD_SOURCE_PATTERN => [
                    'type' => Text::TYPE_NAME,
                    'label' => Redirect::FIELD_SOURCE_PATTERN,
                    'options' => [
                        'dataSource' => Redirect::FIELD_SOURCE_PATTERN,
                    ],
                ],
                Redirect::FIELD_TARGET_PATTERN => [
                    'type' => Text::TYPE_NAME,
                    'label' => Redirect::FIELD_TARGET_PATTERN,
                    'options' => [
                        'dataSource' => Redirect::FIELD_TARGET_PATTERN,
                    ],
                ],
            ],
        ],
    ]
];