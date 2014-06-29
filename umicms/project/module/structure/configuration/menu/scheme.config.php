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
use umicms\project\Environment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/scheme/collection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/scheme/active.config.php',
    [
        'name' => 'menu',
        'columns'     =>  [
            'name' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'page_relation' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'url_resource' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ]
        ],
        'indexes' => [
            'name' => [
                'type' => 'unique',
                'columns' => [
                    'name' => []
                ]
            ]
        ]
    ]
);
