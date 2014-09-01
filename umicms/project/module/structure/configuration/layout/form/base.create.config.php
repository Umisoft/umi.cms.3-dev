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
use umicms\project\module\structure\model\object\Layout;

return [

    'options' => [
        'dictionaries' => [
            'collection.layout' => 'collection.layout', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Layout::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Layout::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Layout::FIELD_DISPLAY_NAME
                    ],
                ],
                Layout::FIELD_FILE_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Layout::FIELD_FILE_NAME,
                    'options' => [
                        'dataSource' => Layout::FIELD_FILE_NAME
                    ],
                ]
            ]
        ]
    ]
];