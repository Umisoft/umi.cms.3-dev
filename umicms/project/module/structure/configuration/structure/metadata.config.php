<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\Environment;
use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\structure\model\object\SystemPage;

var_dump( array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/hierarchicPageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'structure'
        ],
        'fields'     => [
            StructureElement::FIELD_COMPONENT_NAME     => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'component_name',
                'readOnly'   => true
            ],
            StructureElement::FIELD_COMPONENT_PATH     => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'component_path',
                'readOnly'   => true
            ],
            SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => [
                'type'         => IField::TYPE_BOOL,
                'columnName'   => 'skip_in_breadcrumbs',
                'defaultValue' => 0
            ],
            StructureElement::FIELD_IN_MENU            => [
                'type'         => IField::TYPE_BOOL,
                'columnName'   => 'in_menu',
                'defaultValue' => 0
            ],
            StructureElement::FIELD_SUBMENU_STATE      => [
                'type'         => IField::TYPE_INTEGER,
                'columnName'   => 'submenu_state',
                'defaultValue' => 0
            ]
        ],
        'types'      => [
            'base'           => [
                'objectClass' => 'umicms\project\module\structure\model\object\StructureElement',
                'fields'      => [
                    StructureElement::FIELD_COMPONENT_NAME,
                    StructureElement::FIELD_COMPONENT_PATH,
                    StructureElement::FIELD_IN_MENU,
                    StructureElement::FIELD_SUBMENU_STATE
                ]
            ],
            SystemPage::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\SystemPage',
                'fields'      => [
                    SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS,
                ]
            ],
            StaticPage::TYPE => [
                'objectClass' => 'umicms\project\module\structure\model\object\StaticPage'
            ]
        ]
    ]
));
exit;
