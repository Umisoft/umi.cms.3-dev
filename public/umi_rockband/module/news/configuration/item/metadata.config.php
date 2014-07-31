<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;

return [
    'fields' => [
        'image' => [
            'type' => IField::TYPE_STRING,
            'columnName' => 'image'
        ]
    ],
    'types' => [
        'base' => [
            'objectClass' => 'umicms\project\module\news\model\object\NewsItem',
            'fields' => [
                'image' => []
            ]
        ]
    ],
];
