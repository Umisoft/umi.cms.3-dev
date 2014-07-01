<?php

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