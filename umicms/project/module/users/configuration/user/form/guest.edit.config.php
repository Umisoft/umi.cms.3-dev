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
use umicms\project\module\users\model\object\Guest;

return [

    'options' => [
        'dictionaries' => [
            'collection.user' => 'collection.user', 'collection' => 'collection', 'form' => 'form'
        ]
    ],

    'elements' => [

        'common' => [
            'type' => FieldSet::TYPE_NAME,
            'label' => 'common',
            'elements' => [
                Guest::FIELD_DISPLAY_NAME => [
                    'type' => Text::TYPE_NAME,
                    'label' => Guest::FIELD_DISPLAY_NAME,
                    'options' => [
                        'dataSource' => Guest::FIELD_DISPLAY_NAME
                    ],
                ]
            ]
        ]
    ]
];