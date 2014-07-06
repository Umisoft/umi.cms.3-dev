<?php

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