<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема колонок для коллекций,
 * поддерживающих удаление объектов в корзину.
 */
return [
    'columns' => [
        'trashed'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0
            ]
        ]
    ]
];