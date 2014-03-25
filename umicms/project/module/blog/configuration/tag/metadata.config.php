<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\metadata\field\IField;
use umi\orm\object\IObject;
use umicms\orm\object\CmsObject;

return [
    'dataSource' => [
        'sourceName' => 'umi_blog_tag'
    ],
    'fields'     => [
        IObject::FIELD_IDENTIFY => [
            'type'       => IField::TYPE_IDENTIFY,
            'columnName' => 'id',
            'accessor'   => 'getId'
        ],
        IObject::FIELD_GUID     => [
            'type'       => IField::TYPE_GUID,
            'columnName' => 'guid',
            'accessor'   => 'getGuid',
            'mutator'    => 'setGuid'
        ],
        IObject::FIELD_TYPE     => [
            'type'       => IField::TYPE_STRING,
            'columnName' => 'type',
            'accessor'   => 'getType',
            'readOnly'   => true
        ],
        IObject::FIELD_VERSION  => [
            'type'         => IField::TYPE_VERSION,
            'columnName'   => 'version',
            'accessor'     => 'getVersion',
            'mutator'      => 'setVersion',
            'defaultValue' => 1
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
        'displayName'           => ['type' => IField::TYPE_STRING, 'columnName' => 'display_name'],
        'active'                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
        'locked'                => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'defaultValue' => 0
        ],
        'created'               => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'created'],
        'updated'               => ['type' => IField::TYPE_DATE_TIME, 'columnName' => 'updated'],
        'h1'                    => ['type' => IField::TYPE_STRING, 'columnName' => 'h1'],
        'metaTitle'             => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_title'],
        'metaKeywords'          => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_keywords'],
        'metaDescription'       => ['type' => IField::TYPE_STRING, 'columnName' => 'meta_description'],
        'contents'               => ['type' => IField::TYPE_TEXT, 'columnName' => 'contents'],
        'slug'                  => [
            'type'       => IField::TYPE_SLUG,
            'columnName' => 'slug',
            'accessor'   => 'getSlug',
            'mutator'    => 'setSlug'
        ],
        'posts'                 => [
            'type'         => IField::TYPE_MANY_TO_MANY,
            'target'       => 'blogPost',
            'bridge'       => 'blogPostTag',
            'relatedField' => 'tag',
            'targetField'  => 'post'
        ]

    ],
    'types'      => [
        'base' => [
            'fields' => [
                'id',
                'guid',
                'type',
                'version',
                'active',
                'locked',
                'created',
                'updated',
                CmsObject::FIELD_OWNER,
                CmsObject::FIELD_EDITOR,
                'displayName',
                'h1',
                'metaTitle',
                'metaKeywords',
                'metaDescription',
                'contents',
                'slug',
                'posts'
            ]
        ]
    ]
];