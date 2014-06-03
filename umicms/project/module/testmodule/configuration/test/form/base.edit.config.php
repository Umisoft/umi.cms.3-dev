<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\CheckboxGroup;
use umi\form\element\html5\Date;
use umi\form\element\html5\DateTime;
use umi\form\element\html5\Email;
use umi\form\element\html5\Number;
use umi\form\element\html5\Time;
use umi\form\element\Password;
use umi\form\element\Radio;
use umi\form\element\Select;
use umi\form\element\MultiSelect;
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
                TestObject::SELECT => [
                    'type' => Select::TYPE_NAME,
                    'label' => TestObject::SELECT,
                    'options' => [
                        'dataSource' => TestObject::SELECT,
                        'choices' => [
                            'msk' => 'Москва',
                            'spt' => 'СПб',
                            'vlg' => 'Волгоград',
                            'rostov' => 'Ростов',
                            'krasnodar' => 'Краснодар'
                        ]
                    ],
                ],
                TestObject::RADIO => [
                    'type' => Radio::TYPE_NAME,
                    'label' => TestObject::RADIO,
                ],
                TestObject::PASSWORD => [
                    'type' => Password::TYPE_NAME,
                    'label' => TestObject::PASSWORD,
                    'options' => [
                        'dataSource' => TestObject::PASSWORD
                    ],
                ],
                TestObject::MULTISELECT => [
                    'type' => MultiSelect::TYPE_NAME,
                    'label' => TestObject::MULTISELECT,
                    'options' => [
                        'dataSource' => TestObject::MULTISELECT,
                        'choices' => [
                            'msk' => 'Геленджик',
                            'spt' => 'Ашхабад',
                            'vlg' => 'Куйрам-Байрам',
                            'rostov' => 'Ростов',
                            'krasnodar' => 'Краснодар'
                        ]
                    ],
                ],
                TestObject::CHECKBOX_GROUP => [
                    'type' => CheckboxGroup::TYPE_NAME,
                    'label' => TestObject::CHECKBOX_GROUP,
                    'options' => [
                        'dataSource' => TestObject::CHECKBOX_GROUP,
                        'choices' => [
                            'msk' => 'Москва',
                            'spt' => 'СПб',
                            'vlg' => 'Волгоград',
                            'rostov' => 'Ростов',
                            'krasnodar' => 'Краснодар'
                        ]
                    ],
                ],
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