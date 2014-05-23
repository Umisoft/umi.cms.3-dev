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
use umi\form\element\Select;
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
        'checkSettings' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'checkSettings',
            'elements' => [
                'checkMode' => [
                    'type' => Select::TYPE_NAME,
                    'label' => 'checkMode',
                    'options' => [
                        'choices' => [
                            'guest' => 'checkMode:guest',
                            'all' => 'checkMode:all',
                            'never' => 'checkMode:never'
                        ],
                        'dataSource' => 'checkMode'
                    ]
                ],
                'humanTestsCount' => [
                    'type' => Text::TYPE_NAME,
                    'label' => 'humanTestsCount',
                    'options' => [
                        'filters' => [
                            IFilterFactory::TYPE_INT => []
                        ],
                        'dataSource' => 'humanTestsCount'
                    ]
                ]
            ]
        ],
        'viewSettings' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'viewSettings',
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