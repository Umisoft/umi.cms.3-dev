<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umi\validation\IValidatorFactory;
use umicms\project\module\structure\api\object\BaseMenu;
use umicms\project\module\structure\api\object\Menu;
use umicms\project\module\structure\api\object\MenuExternalItem;
use umicms\project\module\structure\api\object\MenuInternalItem;

return [
    'dataSource' => [
        'sourceName' => 'umi_menu'
    ],
    'fields' => [

        BaseMenu::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        BaseMenu::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        BaseMenu::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        BaseMenu::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        BaseMenu::FIELD_PARENT => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'pid',
            'accessor' => 'getParent',
            'target' => Menu::TYPE,
            'readOnly' => true
        ],
        BaseMenu::FIELD_MPATH => [
            'type' => IField::TYPE_MPATH,
            'columnName' => 'mpath',
            'accessor' => 'getMaterializedPath',
            'readOnly' => true
        ],
        BaseMenu::FIELD_SLUG => [
            'type' => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor' => 'getSlug'
        ],
        BaseMenu::FIELD_URI => [
            'type' => IField::TYPE_URI,
            'columnName' => 'uri',
            'accessor' => 'getURI',
            'readOnly' => true
        ],
        BaseMenu::FIELD_CHILD_COUNT => [
            'type' => IField::TYPE_COUNTER,
            'columnName' => 'child_count',
            'accessor' => 'getChildCount',
            'readOnly' => true,
            'defaultValue' => 0
        ],
        BaseMenu::FIELD_ORDER => [
            'type' => IField::TYPE_ORDER,
            'columnName' => 'order',
            'accessor' => 'getOrder',
            'readOnly' => true
        ],
        BaseMenu::FIELD_HIERARCHY_LEVEL => [
            'type' => IField::TYPE_LEVEL,
            'columnName' => 'level',
            'accessor' => 'getLevel',
            'readOnly' => true
        ],
        BaseMenu::FIELD_ACTIVE => [
            'type' => IField::TYPE_BOOL,
            'columnName' => 'active',
            'defaultValue' => 1
        ],
        Menu::FIELD_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'name',
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
        ],
        BaseMenu::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'filters' => [
                IFilterFactory::TYPE_STRING_TRIM => []
            ],
            'validators' => [
                IValidatorFactory::TYPE_REQUIRED => []
            ],
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        BaseMenu::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created'
        ],
        BaseMenu::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated'
        ],
        BaseMenu::FIELD_CHILDREN => [
            'type' => IField::TYPE_HAS_MANY,
            'target' => Menu::TYPE,
            'targetField' => BaseMenu::FIELD_PARENT,
            'readOnly' => true
        ],
        BaseMenu::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        BaseMenu::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        MenuInternalItem::FIELD_COLLECTION_NAME_ITEM => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'collection_name'
        ],
        MenuInternalItem::FIELD_ITEM_ID => [
            'type' => IField::TYPE_INTEGER,
            'columnName' => 'item_id'
        ],
        MenuExternalItem::FIELD_URL_RESOURCE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'url_resource'
        ]
    ],
    'types' => [
        IObjectType::BASE => [
            'objectClass' => 'umicms\project\module\structure\api\object\BaseMenu',
            'fields' => [
                BaseMenu::FIELD_IDENTIFY,
                BaseMenu::FIELD_GUID,
                BaseMenu::FIELD_TYPE,
                BaseMenu::FIELD_VERSION,
                BaseMenu::FIELD_ACTIVE,
                BaseMenu::FIELD_DISPLAY_NAME,
                BaseMenu::FIELD_PARENT,
                BaseMenu::FIELD_MPATH,
                BaseMenu::FIELD_SLUG,
                BaseMenu::FIELD_URI,
                BaseMenu::FIELD_HIERARCHY_LEVEL,
                BaseMenu::FIELD_ORDER,
                BaseMenu::FIELD_CHILD_COUNT,
                BaseMenu::FIELD_CREATED,
                BaseMenu::FIELD_UPDATED,
                BaseMenu::FIELD_CHILDREN,
                BaseMenu::FIELD_OWNER,
                BaseMenu::FIELD_EDITOR
            ]
        ],
        Menu::TYPE => [
            'objectClass' => 'umicms\project\module\structure\api\object\Menu',
            'fields' => [
                Menu::FIELD_IDENTIFY,
                Menu::FIELD_GUID,
                Menu::FIELD_TYPE,
                Menu::FIELD_VERSION,
                Menu::FIELD_ACTIVE,
                Menu::FIELD_PARENT,
                Menu::FIELD_NAME,
                Menu::FIELD_DISPLAY_NAME,
                Menu::FIELD_PARENT,
                Menu::FIELD_MPATH,
                Menu::FIELD_SLUG,
                Menu::FIELD_URI,
                Menu::FIELD_HIERARCHY_LEVEL,
                Menu::FIELD_ORDER,
                Menu::FIELD_CHILD_COUNT,
                Menu::FIELD_CREATED,
                Menu::FIELD_UPDATED,
                Menu::FIELD_CHILDREN,
                Menu::FIELD_OWNER,
                Menu::FIELD_EDITOR
            ]
        ],
        MenuInternalItem::TYPE => [
            'objectClass' => 'umicms\project\module\structure\api\object\MenuInternalItem',
            'fields' => [
                MenuInternalItem::FIELD_IDENTIFY,
                MenuInternalItem::FIELD_GUID,
                MenuInternalItem::FIELD_TYPE,
                MenuInternalItem::FIELD_VERSION,
                MenuInternalItem::FIELD_ACTIVE,
                MenuInternalItem::FIELD_PARENT,
                MenuInternalItem::FIELD_DISPLAY_NAME,
                MenuInternalItem::FIELD_PARENT,
                MenuInternalItem::FIELD_MPATH,
                MenuInternalItem::FIELD_SLUG,
                MenuInternalItem::FIELD_URI,
                MenuInternalItem::FIELD_HIERARCHY_LEVEL,
                MenuInternalItem::FIELD_ORDER,
                MenuInternalItem::FIELD_CHILD_COUNT,
                MenuInternalItem::FIELD_CREATED,
                MenuInternalItem::FIELD_UPDATED,
                MenuInternalItem::FIELD_CHILDREN,
                MenuInternalItem::FIELD_OWNER,
                MenuInternalItem::FIELD_EDITOR,
                MenuInternalItem::FIELD_COLLECTION_NAME_ITEM,
                MenuInternalItem::FIELD_ITEM_ID
            ]
        ],
        MenuExternalItem::TYPE => [
            'objectClass' => 'umicms\project\module\structure\api\object\MenuExternalItem',
            'fields' => [
                MenuExternalItem::FIELD_IDENTIFY,
                MenuExternalItem::FIELD_GUID,
                MenuExternalItem::FIELD_TYPE,
                MenuExternalItem::FIELD_VERSION,
                MenuExternalItem::FIELD_ACTIVE,
                MenuExternalItem::FIELD_PARENT,
                MenuExternalItem::FIELD_DISPLAY_NAME,
                MenuExternalItem::FIELD_PARENT,
                MenuExternalItem::FIELD_MPATH,
                MenuExternalItem::FIELD_SLUG,
                MenuExternalItem::FIELD_URI,
                MenuExternalItem::FIELD_HIERARCHY_LEVEL,
                MenuExternalItem::FIELD_ORDER,
                MenuExternalItem::FIELD_CHILD_COUNT,
                MenuExternalItem::FIELD_CREATED,
                MenuExternalItem::FIELD_UPDATED,
                MenuExternalItem::FIELD_CHILDREN,
                MenuExternalItem::FIELD_OWNER,
                MenuExternalItem::FIELD_EDITOR,
                MenuExternalItem::FIELD_URL_RESOURCE
            ]
        ],

    ]
];
