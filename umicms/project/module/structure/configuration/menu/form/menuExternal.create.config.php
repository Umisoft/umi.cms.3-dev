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
use umicms\project\module\structure\model\object\MenuExternalItem;

return [

    'options' => [
        'dictionaries' => [
            'collection.menu' => 'collection.menu', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                MenuExternalItem::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => MenuExternalItem::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => MenuExternalItem::FIELD_DISPLAY_NAME,
                        'filters'    => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ]
                    ],
                ]
            ]
        ],

        'settings' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'settings',
            'elements' => [
                MenuExternalItem::FIELD_RESOURCE_URL => [
                    'type' => Text::TYPE_NAME,
                    'label' => MenuExternalItem::FIELD_RESOURCE_URL,
                    'options' => [
                        'dataSource' => MenuExternalItem::FIELD_RESOURCE_URL,
                        'filters'    => [
                            IFilterFactory::TYPE_STRING_TRIM => []
                        ],
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ]
                    ],
                ]
            ]
        ]
    ]
];