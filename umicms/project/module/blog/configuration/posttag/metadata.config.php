<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\orm\object\CmsObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_blog_post_tags'
    ],
    'fields' => [
        CmsObject::FIELD_IDENTIFY => [
            'type' => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor' => 'getId',
            'readOnly' => true
        ],
        CmsObject::FIELD_GUID => [
            'type' => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor' => 'getGuid',
            'readOnly' => true
        ],
        CmsObject::FIELD_TYPE => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor' => 'getType',
            'readOnly' => true
        ],
        CmsObject::FIELD_VERSION => [
            'type' => IField::TYPE_VERSION,
            'columnName' => 'version',
            'accessor' => 'getVersion',
            'readOnly' => true,
            'defaultValue' => 1
        ],
        CmsObject::FIELD_DISPLAY_NAME => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'display_name',
            'localizations' => [
                'ru-RU' => ['columnName' => 'display_name'],
                'en-US' => ['columnName' => 'display_name_en']
            ]
        ],
        CmsObject::FIELD_CREATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'created',
            'readOnly' => true
        ],
        CmsObject::FIELD_UPDATED => [
            'type' => IField::TYPE_DATE_TIME,
            'columnName' => 'updated',
            'readOnly' => true
        ],
        CmsObject::FIELD_OWNER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'owner_id',
            'target' => 'user'
        ],
        CmsObject::FIELD_EDITOR => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'editor_id',
            'target' => 'user'
        ],
        'blogPost' => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'post_id',
            'target' => 'blogPost'
        ],
        'tag' => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'tag_id',
            'target' => 'blogTag'
        ]
    ],
    'types' => [
        'base' => [
            'fields' => [
                CmsObject::FIELD_IDENTIFY,
                CmsObject::FIELD_GUID,
                CmsObject::FIELD_TYPE,
                CmsObject::FIELD_VERSION,
                CmsObject::FIELD_OWNER,
                CmsObject::FIELD_EDITOR,
                CmsObject::FIELD_DISPLAY_NAME,
                CmsObject::FIELD_CREATED,
                CmsObject::FIELD_UPDATED,
                CmsObject::FIELD_OWNER,
                CmsObject::FIELD_EDITOR,
                'blogPost',
                'tag'
            ]
        ]
    ]
];