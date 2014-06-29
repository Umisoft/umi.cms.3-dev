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
use umicms\project\Environment;
use umicms\project\module\structure\model\object\Menu;
use umicms\project\module\structure\model\object\MenuExternalItem;
use umicms\project\module\structure\model\object\MenuInternalItem;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/collection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/metadata/active.config.php',
    [
        'dataSource' => [
            'sourceName' => 'menu'
        ],
        'fields'     => [
            Menu::FIELD_NAME                      => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            MenuInternalItem::FIELD_PAGE_RELATION => [
                'type'       => IField::TYPE_OBJECT_RELATION,
                'columnName' => 'page_relation',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ],
            MenuExternalItem::FIELD_RESOURCE_URL  => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'url_resource',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => []
                ]
            ]
        ],
        'types'      => [
            IObjectType::BASE      => [
                'objectClass' => 'umicms\project\module\structure\model\object\BaseMenu',
                'fields'      => []
            ],
            Menu::TYPE             => [
                'objectClass' => 'umicms\project\module\structure\model\object\Menu',
                'fields'      => [
                    Menu::FIELD_NAME
                ]
            ],
            MenuInternalItem::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\MenuInternalItem',
                'fields'      => [
                    MenuInternalItem::FIELD_PAGE_RELATION
                ]
            ],
            MenuExternalItem::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\MenuExternalItem',
                'fields'      => [
                    MenuExternalItem::FIELD_RESOURCE_URL
                ]
            ],

        ]
    ]
);
