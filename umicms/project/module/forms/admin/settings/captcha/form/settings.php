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
use umi\form\element\html5\Color;
use umi\form\element\Select;
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umi\validation\IValidatorFactory;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.forms.captcha'
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
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ],
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