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
use umicms\orm\object\behaviour\IRecyclableObject;

/**
 * Метаданные для коллекций, поддерживающих удаление объектов в корзину.
 */
return [
    'fields' => [
        IRecyclableObject::FIELD_TRASHED => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'trashed',
            'defaultValue' => 0,
            'readOnly'     => true,
        ]
    ],
    'types'  => [
        'base' => [
            'fields' => [
                IRecyclableObject::FIELD_TRASHED => []
            ]
        ]
    ]
];