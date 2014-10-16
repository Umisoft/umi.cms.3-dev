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
use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umi\validation\IValidatorFactory;
use umicms\project\module\users\model\object\BaseUser;

return [

    'options' => [
        'dictionaries' => [
            'collection.user' => 'collection.user', 'collection' => 'collection'
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
                        'dataSource' => BaseUser::FIELD_DISPLAY_NAME,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ],
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => [],
                            IFilterFactory::TYPE_STRIP_TAGS => []
                        ],
                    ],
                ]
            ]
        ]
    ]
];