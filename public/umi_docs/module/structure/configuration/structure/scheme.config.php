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

return
    [
    'columns'     =>  [
        'parameters' => [
            'type' => Type::TEXT
        ],
        'description' => [
            'type' => Type::TEXT
        ],
        'twig_example' => [
            'type' => Type::TEXT
        ],
        'php_example' => [
            'type' => Type::TEXT
        ],
        'second_contents' => [
            'type' => Type::TEXT
        ],
        'return_value' => [
            'type' => Type::TEXT
        ],
        'template_name' => [
            'type' => Type::STRING
        ]
    ]
];
