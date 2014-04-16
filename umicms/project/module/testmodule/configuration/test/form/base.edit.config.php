<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\form\element\Checkbox;
use umi\form\element\html5\Date;
use umi\form\element\html5\DateTime;
use umi\form\element\html5\Email;
use umi\form\element\html5\Number;
use umi\form\element\html5\Time;
use umi\form\element\Radio;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umicms\form\element\File;
use umicms\form\element\Image;
use umicms\project\module\testmodule\api\object\TestObject;

return [

    'options' => [
        'dictionaries' => [
            'collection.test', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                TestObject::TEXT => [
                    'type' => Text::TYPE_NAME,
                    'label' => TestObject::TEXT,
                    'options' => [
                        'dataSource' => TestObject::TEXT
                    ],
                ],
                TestObject::TEXTAREA => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => TestObject::TEXTAREA,
                    'options' => [
                        'dataSource' => TestObject::TEXTAREA
                    ],
                ],
                /*Test::SELECT => [
                    'type' => Select::TYPE_NAME,
                    'label' => Test::SELECT,
                    'options' => [
                        'choices' => [
                            'msk' => 'Москва',
                            'spt' => 'СПб'
                        ]
                    ],
                ],*/
                TestObject::RADIO => [
                    'type' => Radio::TYPE_NAME,
                    'label' => TestObject::RADIO,
                ],
                TestObject::PASSWORD => [
                    'type' => Radio::TYPE_NAME,
                    'label' => TestObject::PASSWORD,
                    'options' => [
                        'dataSource' => TestObject::PASSWORD
                    ],
                ],
                /*Test::MULTISELECT => [
                    'type' => Select::TYPE_NAME,
                    'label' => Test::SELECT,
                    'options' => [
                        'choices' => [
                            'msk' => 'Москва',
                            'spt' => 'СПб'
                        ]
                    ],
                ],*/
                /*Test::CHECKBOX_GROUP => [
                    'type' => CSRF::TYPE_NAME,
                    'label' => Test::CSRF,
                    'options' => [
                        'dataSource' => Test::CSRF
                    ],
                ],*/
                TestObject::CHECKBOX => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => TestObject::CHECKBOX,
                    'options' => [
                        'dataSource' => TestObject::CHECKBOX
                    ],
                ],

                TestObject::DATE => [
                    'type' => Date::TYPE_NAME,
                    'label' => TestObject::DATE,
                    'options' => [
                        'dataSource' => TestObject::DATE
                    ],
                ],
                TestObject::DATE_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => TestObject::DATE_TIME,
                    'options' => [
                        'dataSource' => TestObject::DATE_TIME
                    ],
                ],
                TestObject::EMAIL => [
                    'type' => Email::TYPE_NAME,
                    'label' => TestObject::EMAIL,
                    'options' => [
                        'dataSource' => TestObject::EMAIL
                    ],
                ],
                TestObject::NUMBER => [
                    'type' => Number::TYPE_NAME,
                    'label' => TestObject::NUMBER,
                    'options' => [
                        'dataSource' => TestObject::NUMBER
                    ],
                ],
                TestObject::TIME => [
                    'type' => Time::TYPE_NAME,
                    'label' => TestObject::TIME,
                    'options' => [
                        'dataSource' => TestObject::TIME
                    ],
                ],
                TestObject::FILE => [
                    'type' => File::TYPE_NAME,
                    'label' => TestObject::FILE,
                    'options' => [
                        'dataSource' => TestObject::FILE
                    ],
                ],
                TestObject::IMAGE => [
                    'type' => Image::TYPE_NAME,
                    'label' => TestObject::IMAGE,
                    'options' => [
                        'dataSource' => TestObject::IMAGE
                    ],
                ]
            ]
        ]
    ]
];