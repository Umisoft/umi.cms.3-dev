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
use umicms\form\element\Permissions;
use umicms\project\module\users\model\object\UserGroup;

return [

    'options' => [
        'dictionaries' => [
            'collection.userGroup' => 'collection.userGroup', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                UserGroup::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => UserGroup::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => UserGroup::FIELD_DISPLAY_NAME,
                        'validators' => [
                            IValidatorFactory::TYPE_REQUIRED => []
                        ],
                        'filters' => [
                            IFilterFactory::TYPE_STRING_TRIM => [],
                            IFilterFactory::TYPE_STRIP_TAGS => []
                        ],
                    ]
                ]
            ]

        ],
        'permissions' => [
            'type' => FieldSet::TYPE_NAME,
            'label'=> 'permissions',
            'elements' => [
                UserGroup::FIELD_ROLES => [
                    'type' => Permissions::TYPE_NAME,
                    'label' => UserGroup::FIELD_ROLES,
                    'options' => [
                        'dataSource' => UserGroup::FIELD_ROLES
                    ]
                ]
            ]
        ]
    ]
];