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

return [
    'options' => [
        'dictionaries' => [
            'project.admin.rest.settings.site.mail'
        ]
    ],

    'elements' => [
        'sender_address' => [
            'type' => Text::TYPE_NAME,
            'label' => 'sender_address',
        ],
        'delivery_address' => [
            'type' => Text::TYPE_NAME,
            'label' => 'delivery_address',
        ]
    ]

];