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
use umi\orm\metadata\IObjectType;
use umi\validation\IValidatorFactory;
use umicms\orm\metadata\field\relation\CmsPageRelationField;
use umicms\project\module\structure\model\object\Menu;
use umicms\project\module\structure\model\object\MenuExternalItem;
use umicms\project\module\structure\model\object\MenuInternalItem;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/hierarchicCollection.config.php',
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
                'type'       => CmsPageRelationField::TYPE,
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
                    Menu::FIELD_NAME => []
                ]
            ],
            MenuInternalItem::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\MenuInternalItem',
                'fields'      => [
                    MenuInternalItem::FIELD_PAGE_RELATION => []
                ]
            ],
            MenuExternalItem::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\MenuExternalItem',
                'fields'      => [
                    MenuExternalItem::FIELD_RESOURCE_URL => []
                ]
            ],

        ]
    ]
);
