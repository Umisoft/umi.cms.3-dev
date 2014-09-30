<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Checkbox;
use umi\form\element\Select;
use umicms\project\module\structure\model\object\SystemPage;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.edit.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.structure' => 'collection.structure'
            ]
        ],

        'elements' => [
            'common' => [
                'elements' => [
                    SystemPage::FIELD_IN_MENU => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => SystemPage::FIELD_IN_MENU,
                        'options' => [
                            'dataSource' => SystemPage::FIELD_IN_MENU
                        ],
                    ],
                    SystemPage::FIELD_SUBMENU_STATE => [
                        'type' => Select::TYPE_NAME,
                        'label' => SystemPage::FIELD_SUBMENU_STATE,
                        'options' => [
                            'dataSource' => SystemPage::FIELD_SUBMENU_STATE,
                            'choices' => [
                                SystemPage::SUBMENU_NEVER_SHOWN => 'neverShown',
                                SystemPage::SUBMENU_CURRENT_SHOWN => 'currentShown',
                                SystemPage::SUBMENU_ALWAYS_SHOWN => 'alwaysShown'
                            ]
                        ]
                    ],
                    SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS,
                        'options' => [
                            'dataSource' => SystemPage::FIELD_SKIP_PAGE_IN_BREADCRUMBS
                        ],
                    ]
                ]
            ]
        ]
    ]
);