<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\form\element\html5\DateTime;
use umi\form\element\MultiSelect;
use umi\form\element\Password;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\model\object\RegisteredUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user' => 'collection.user', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                RegisteredUser::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_DISPLAY_NAME,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ],
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => [],
                            IFilterFactory::TYPE_STRIP_TAGS => []
                        ],
                    ],
                ],
                RegisteredUser::FIELD_LOGIN => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_LOGIN,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_LOGIN,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ],
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => [],
                            IFilterFactory::TYPE_STRIP_TAGS => []
                        ],
                    ],
                ],
                RegisteredUser::FIELD_PASSWORD => [
                    'type' => Password::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_PASSWORD,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_PASSWORD
                    ],
                ],
                RegisteredUser::FIELD_EMAIL => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_EMAIL,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_EMAIL,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => [],
                            IValidatorFactory::TYPE_EMAIL    => [],
                        ]
                    ],
                ],
                RegisteredUser::FIELD_REGISTRATION_DATE => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_REGISTRATION_DATE,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_REGISTRATION_DATE
                    ]
                ],
                RegisteredUser::FIELD_GROUPS => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_GROUPS,
                    'options' => [
                        'lazy' => true,
                        'dataSource' => RegisteredUser::FIELD_GROUPS
                    ]
                ],
            ]
        ],
        'personal' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'personal',
            'elements' => [
                RegisteredUser::FIELD_FIRST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_FIRST_NAME,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_FIRST_NAME
                    ],
                ],
                RegisteredUser::FIELD_MIDDLE_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_MIDDLE_NAME,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_MIDDLE_NAME
                    ],
                ],
                RegisteredUser::FIELD_LAST_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => RegisteredUser::FIELD_LAST_NAME,
                    'options' => [
                        'dataSource' => RegisteredUser::FIELD_LAST_NAME
                    ],
                ],
            ]
        ]
    ]
];