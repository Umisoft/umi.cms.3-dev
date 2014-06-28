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
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\service\model\object\Robots;

return [
    'dataSource' => [
        'sourceName' => 'umi_robots'
    ],
    'fields' => [
        Robots::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId'
        ],
        Robots::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'mutator' => 'setGuid'
        ],
        Robots::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        Robots::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'mutator' => 'setVersion',
            'defaultValue' => 1
        ],
        Robots::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        Robots::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        Robots::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created'
        ],
        Robots::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated'
        ],
        Robots::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => [
                    'columnName' => 'display_name',
                    'validators' => [
                        IValidatorFactory::TYPE_REQUIRED => []
                    ]
                ],
                'en-US' => [
                    'columnName' => 'display_name_en'
                ]
            ]
        ],
        Robots::FIELD_PAGE_RELATION => [
            'type' => IField::TYPE_OBJECT_RELATION,
            'columnName' => 'page_relation'
        ],
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\service\model\object\Robots',
            'fields' => [
                Robots::FIELD_IDENTIFY,
                Robots::FIELD_GUID,
                Robots::FIELD_TYPE,
                Robots::FIELD_VERSION,
                Robots::FIELD_OWNER,
                Robots::FIELD_EDITOR,
                Robots::FIELD_CREATED,
                Robots::FIELD_UPDATED,
                Robots::FIELD_DISPLAY_NAME,
                Robots::FIELD_PAGE_RELATION
            ]
        ]
    ]
];
