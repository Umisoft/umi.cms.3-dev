<?php

/**
 * Метаданные иерархической коллекции объектов
 */
use umi\orm\metadata\field\IField;
use umi\orm\object\IHierarchicObject;
use umi\validation\IValidatorFactory;
use umicms\filter\Slug;

/**
 * Метаданные иерархической коллекции объектов
 */
return array_merge_recursive(
    require __DIR__ . '/collection.config.php',
    [
        'fields'     => [
            IHierarchicObject::FIELD_PARENT                => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'pid',
                'accessor'   => 'getParent',
                'target'     => 'newsRubric',
                'readOnly'   => true
            ],
            IHierarchicObject::FIELD_MPATH                 => [
                'type'       => IField::TYPE_MPATH,
                'columnName' => 'mpath',
                'accessor'   => 'getMaterializedPath',
                'readOnly'   => true
            ],
            IHierarchicObject::FIELD_SLUG                  => [
                'type'       => IField::TYPE_SLUG,
                'columnName' => 'slug',
                'accessor'   => 'getSlug',
                'filters' => [
                    Slug::TYPE => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ],
                'readOnly'   => true
            ],
            IHierarchicObject::FIELD_URI                   => [
                'type'       => IField::TYPE_URI,
                'columnName' => 'uri',
                'accessor'   => 'getURI',
                'readOnly'   => true
            ],
            IHierarchicObject::FIELD_CHILD_COUNT           => [
                'type'         => IField::TYPE_COUNTER,
                'columnName'   => 'child_count',
                'accessor'     => 'getChildCount',
                'readOnly'     => true,
                'defaultValue' => 0
            ],
            IHierarchicObject::FIELD_ORDER                 => [
                'type'       => IField::TYPE_ORDER,
                'columnName' => 'order',
                'accessor'   => 'getOrder',
                'readOnly'   => true
            ],
            IHierarchicObject::FIELD_HIERARCHY_LEVEL       => [
                'type'       => IField::TYPE_LEVEL,
                'columnName' => 'level',
                'accessor'   => 'getLevel',
                'readOnly'   => true
            ],
        ],
        'types'      => [
            'base' => [
                'fields'      => [
                    IHierarchicObject::FIELD_PARENT,
                    IHierarchicObject::FIELD_MPATH,
                    IHierarchicObject::FIELD_SLUG,
                    IHierarchicObject::FIELD_URI,
                    IHierarchicObject::FIELD_CHILD_COUNT,
                    IHierarchicObject::FIELD_ORDER,
                    IHierarchicObject::FIELD_HIERARCHY_LEVEL
                ]
            ]
        ]
    ]
);