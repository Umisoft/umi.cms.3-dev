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
use umicms\orm\object\behaviour\ILockedAccessibleObject;

/**
 * Метаданные для коллекций, поддерживающих управление заблокированнойстью объекта на удаление и некоторые операции.
 */
return [
    'fields'     => [
        ILockedAccessibleObject::FIELD_LOCKED              => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'locked',
            'readOnly'     => true,
            'defaultValue' => 0
        ],
    ],
    'types'      => [
        'base' => [
            'fields'      => [
                ILockedAccessibleObject::FIELD_LOCKED => []
            ]
        ]
    ]
];