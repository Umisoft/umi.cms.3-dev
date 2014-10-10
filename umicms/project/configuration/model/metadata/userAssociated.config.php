<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\orm\object\behaviour\IUserAssociatedObject;

/**
 * Метаданные простой коллекции объектов
 */
return [
    'fields' => [
        IUserAssociatedObject::FIELD_USER => [
            'type' => IField::TYPE_BELONGS_TO,
            'columnName' => 'user_id',
            'target' => 'user'
        ],
    ],
    'types' => [
        'base' => [
            'fields' => [
                IUserAssociatedObject::FIELD_USER => [],
            ]
        ]
    ]
];
