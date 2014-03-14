<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\form\element\CSRF;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\element\Textarea;
use umi\form\fieldset\FieldSet;
use umi\validation\IValidatorFactory;
use umicms\project\module\structure\object\StructureElement;

return [

    'attributes' => [
        'name' => 'post',
        'method' => 'post'
    ],
    'elements' => [

        'common' => [
            StructureElement::FIELD_DISPLAY_NAME => [
                'type' => Text::TYPE_NAME,
                'options' => [
                    'filters' => [
                        IFilterFactory::TYPE_STRING_TRIM => []
                    ],
                    'validators' => [
                        IValidatorFactory::TYPE_REQUIRED => []
                    ],
                    'dataSource' => StructureElement::FIELD_DISPLAY_NAME
                ],
            ],
            StructureElement::FIELD_PAGE_LAYOUT => [
                'type' => Select::TYPE_NAME,
                'options' => [
                    'dataSource' => StructureElement::FIELD_PAGE_LAYOUT
                ],
            ]
        ],

        'meta' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                StructureElement::FIELD_PAGE_H1 => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'dataSource' => StructureElement::FIELD_PAGE_H1
                    ],
                ],
                StructureElement::FIELD_PAGE_META_TITLE => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'dataSource' => StructureElement::FIELD_PAGE_META_TITLE
                    ],
                ],
                StructureElement::FIELD_PAGE_META_KEYWORDS => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'dataSource' => StructureElement::FIELD_PAGE_META_KEYWORDS
                    ]
                ],
                StructureElement::FIELD_PAGE_META_DESCRIPTION => [
                    'type' => Text::TYPE_NAME,
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'dataSource' => StructureElement::FIELD_PAGE_META_DESCRIPTION
                    ]
                ]
            ]
        ],

        'contents' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [

                StructureElement::FIELD_PAGE_CONTENTS => [
                    'type' => Textarea::TYPE_NAME,
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'dataSource' => StructureElement::FIELD_PAGE_CONTENTS
                    ]
                ]
            ]
        ],

        'csrf' => [
            'type' => CSRF::TYPE_NAME
        ],
    ]
];