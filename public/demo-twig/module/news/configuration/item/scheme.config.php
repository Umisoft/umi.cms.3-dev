<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;

return [
    'columns' => [
        'image' => [
            'type' => Type::STRING
        ],
        'announcement_image' => [
            'type' => Type::STRING
        ],
        'second_contents'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'second_contents_en'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'simple_text'         => [
            'type' => Type::TEXT,
            'options' => [
                'length' => MySqlPlatform::LENGTH_LIMIT_MEDIUMTEXT
            ]
        ],
        'first_image' => [
            'type'    => Type::STRING
        ],
        'second_image' => [
            'type'    => Type::STRING
        ],
        'file' => [
            'type'    => Type::STRING
        ],
        'popular'          => [
            'type'    => Type::BOOLEAN,
            'options' => [
                'default' => 0,
                'notnull' => true
            ]
        ]
    ]
];
