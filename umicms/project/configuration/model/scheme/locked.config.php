<?php

use Doctrine\DBAL\Types\Type;

/**
 * Схема колонок для коллекций,
 * поддерживающих управление заблокированнойстью объекта на удаление и некоторые операции.
 */
return [
    'columns' => [
        'locked'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0,
                'notnull' => true
            ]
        ]
    ]
];