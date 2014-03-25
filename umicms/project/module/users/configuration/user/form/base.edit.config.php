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
use umicms\project\module\users\api\object\BaseUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user', 'collection'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                BaseUser::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => BaseUser::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => BaseUser::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ]
    ]
];