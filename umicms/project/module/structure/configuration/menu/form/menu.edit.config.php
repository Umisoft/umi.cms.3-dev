<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Text;
use umi\form\fieldset\FieldSet;
use umicms\project\module\structure\model\object\Menu;

return [

    'options' => [
        'dictionaries' => [
            'collection.menu', 'collection', 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Menu::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Menu::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Menu::FIELD_DISPLAY_NAME
                    ],
                ],
                Menu::FIELD_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Menu::FIELD_NAME,
                    'options' => [
                        'dataSource' => Menu::FIELD_NAME
                    ],
                ],
            ]
        ]
    ]
];