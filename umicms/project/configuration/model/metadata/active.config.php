<?php

use umi\orm\metadata\field\IField;
use umicms\orm\object\behaviour\IActiveAccessibleObject;

/**
 * Метаданные для коллекций, поддерживающих управлению активностью объекта на сайте.
 */
return [
    'fields'     => [
        IActiveAccessibleObject::FIELD_ACTIVE => [
            'type'         => IField::TYPE_BOOL,
            'columnName'   => 'active',
            'defaultValue' => 1
        ],
    ],
    'types'      => [
        'base' => [
            'fields'      => [
                IActiveAccessibleObject::FIELD_ACTIVE => []
            ]
        ]
    ]
];