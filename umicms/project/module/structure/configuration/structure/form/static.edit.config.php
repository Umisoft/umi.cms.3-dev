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
use umicms\project\module\structure\model\object\StaticPage;

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
                    StaticPage::FIELD_IN_MENU => [
                        'type' => Checkbox::TYPE_NAME,
                        'label' => StaticPage::FIELD_IN_MENU,
                        'options' => [
                            'dataSource' => StaticPage::FIELD_IN_MENU
                        ],
                    ],
                    StaticPage::FIELD_SUBMENU_STATE => [
                        'type' => Select::TYPE_NAME,
                        'label' => StaticPage::FIELD_SUBMENU_STATE,
                        'options' => [
                            'dataSource' => StaticPage::FIELD_SUBMENU_STATE,
                            'choices' => [
                                StaticPage::SUBMENU_NEVER_SHOWN => 'neverShown',
                                StaticPage::SUBMENU_CURRENT_SHOWN => 'currentShown',
                                StaticPage::SUBMENU_ALWAYS_SHOWN => 'alwaysShown'
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ]
);