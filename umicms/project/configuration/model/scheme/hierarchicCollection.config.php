<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Types\Type;

/**
 * Схема таблицы для простой иерархической коллекции объектов
 */
return array_replace_recursive(
    require __DIR__ . '/collection.config.php',
    [
        'columns' => [
            'parent_id' => [
                'type'    => Type::BIGINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'mpath' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 255
                ]
            ],
            'slug'             => [
                'type' => Type::STRING
            ],
            'uri' => [
                'type' => Type::STRING,
                'options' => [
                    'length' => 255
                ]
            ],
            'order' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'level' => [
                'type'    => Type::SMALLINT,
                'options' => [
                    'unsigned' => true
                ]
            ],
            'site_child_count' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ],
            'site_child_count_en' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ],
            'admin_child_count' => [
                'type'    => Type::INTEGER,
                'options' => [
                    'unsigned' => true,
                    'notnull' => true,
                    'default' => 0
                ]
            ]
        ],
        'indexes' => [
            'slug' => [
                'columns' => [
                    'slug' => []
                ]
            ],
            'uri' => [
                'type' => 'unique',
                'columns' => [
                    'uri' => []
                ]
            ],
            'mpath' => [
                'type' => 'unique',
                'columns' => [
                    'mpath' => []
                ]
            ],
            'parent' => [
                'columns' => [
                    'parent_id' => []
                ]
            ]
        ],
        'constraints' => [
            'to_parent' => [
                'columns' => [
                    'parent_id' => []
                ],
                'options' => [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            ]
        ]
    ]
);