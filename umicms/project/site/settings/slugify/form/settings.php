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
use umi\form\element\Checkbox;
use umi\form\element\Text;
use umicms\validation\Range;

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site.slugify' => 'project.admin.rest.settings.site.slugify'
        ]
    ],

    'elements' => [
        'slugTranslit' => [
            'type' => Checkbox::TYPE_NAME,
            'label' => 'slugTranslit',
            'options' => [
                'dataSource' => 'slugTranslit'
            ]
        ],
        'slugLength' => [
            'type' => Text::TYPE_NAME,
            'label' => 'slugLength',
            'options' => [
                'filters' => [
                    IFilterFactory::TYPE_INT => []
                ],
                'validators' => [
                    Range::NAME => [
                        'min' => 1,
                        'max' => 60
                    ]
                ],
                'dataSource' => 'options.slugLength'
            ]

        ]
    ]
];