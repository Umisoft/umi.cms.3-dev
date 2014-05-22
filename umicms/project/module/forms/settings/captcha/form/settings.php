<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\form\element\html5\Color;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\form\element\Captcha;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.settings.forms.captcha'
        ]
    ],

    'elements' => [
        'security' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                'captcha' => [
                    'type' => Captcha::TYPE_NAME,

                ]
            ]
        ],
        'viewSettings' => [
            'type' => FieldSet::TYPE_NAME,
            'elements' => [
                'width' => [
                    'type' => Text::TYPE_NAME,
                    'label' => 'width',
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_INT => []
                        ],
                        'dataSource' => 'width'
                    ]
                ],
                'height' => [
                    'type' => Text::TYPE_NAME,
                    'label' => 'height',
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_INT => []
                        ],
                        'dataSource' => 'height'
                    ]
                ],
                'textColor' => [
                    'type' => Color::TYPE_NAME,
                    'label' => 'textColor',
                    'options'  => [
                        'dataSource' => 'textColor'
                    ]
                ],
                'backgroundColor' => [
                    'type' => Color::TYPE_NAME,
                    'label' => 'backgroundColor',
                    'options'  => [
                        'dataSource' => 'backgroundColor'
                    ]
                ]
            ]
        ],

    ]
];