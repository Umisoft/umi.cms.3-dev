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
use umicms\project\module\testmodule\api\object\Test;

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
                Test::TEXT => [
                    'type' => Text::TYPE_NAME,
                    'label' => Test::TEXT,
                    'options' => [
                        'dataSource' => Test::TEXT
                    ],
                ],
                Test::TEXTAREA => [
                    'type' => Textarea::TYPE_NAME,
                    'label' => Test::TEXTAREA,
                    'options' => [
                        'dataSource' => Test::TEXTAREA
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
                Test::RADIO => [
                    'type' => Radio::TYPE_NAME,
                    'label' => Test::RADIO,
                ],
                Test::PASSWORD => [
                    'type' => Radio::TYPE_NAME,
                    'label' => Test::PASSWORD,
                    'options' => [
                        'dataSource' => Test::PASSWORD
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
                Test::CHECKBOX => [
                    'type' => Checkbox::TYPE_NAME,
                    'label' => Test::CHECKBOX,
                    'options' => [
                        'dataSource' => Test::CHECKBOX
                    ],
                ],

                Test::DATE => [
                    'type' => Date::TYPE_NAME,
                    'label' => Test::DATE,
                    'options' => [
                        'dataSource' => Test::DATE
                    ],
                ],
                Test::DATE_TIME => [
                    'type' => DateTime::TYPE_NAME,
                    'label' => Test::DATE_TIME,
                    'options' => [
                        'dataSource' => Test::DATE_TIME
                    ],
                ],
                Test::EMAIL => [
                    'type' => Email::TYPE_NAME,
                    'label' => Test::EMAIL,
                    'options' => [
                        'dataSource' => Test::EMAIL
                    ],
                ],
                Test::NUMBER => [
                    'type' => Number::TYPE_NAME,
                    'label' => Test::NUMBER,
                    'options' => [
                        'dataSource' => Test::NUMBER
                    ],
                ],
                Test::TIME => [
                    'type' => Time::TYPE_NAME,
                    'label' => Test::TIME,
                    'options' => [
                        'dataSource' => Test::TIME
                    ],
                ],
                Test::FILE => [
                    'type' => File::TYPE_NAME,
                    'label' => Test::FILE,
                    'options' => [
                        'dataSource' => Test::FILE
                    ],
                ],
                Test::IMAGE => [
                    'type' => Image::TYPE_NAME,
                    'label' => Test::IMAGE,
                    'options' => [
                        'dataSource' => Test::IMAGE
                    ],
                ]
            ]
        ]
    ]
];