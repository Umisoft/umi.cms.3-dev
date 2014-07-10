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

return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/scheme/hierarchicPageCollection.config.php',
    require Environment::$directoryCmsProject . '/configuration/model/scheme/locked.config.php',
    [
        'name' => 'structure',
        'columns'     =>  [
            'component_name' => [
                'type' => Type::STRING,
                'options' => [
                    'default' => 'structure',
                    'notnull' => true
                ]
            ],
            'component_path' => [
                'type' => Type::STRING,
                'options' => [
                    'default' => 'structure',
                    'notnull' => true
                ]
            ],
            'in_menu'          => [
                'type'    => Type::BOOLEAN,
                'options' => [
                    'default' => 0,
                    'notnull' => true
                ]
            ],
            'submenu_state' => [
                'type'    => Type::SMALLINT,
                'options' => [
                    'default' => 0,
                    'notnull' => true
                ]
            ],
            'skip_in_breadcrumbs' => [
                'type'    => Type::BOOLEAN,
                'options' => [
                    'default' => 0,
                    'notnull' => true
                ]
            ]
        ],
        'indexes'     => [
            'component_name' => [
                'columns' => [
                    'component_name' => []
                ]
            ],
            'component_path' => [
                'columns' => [
                    'component_path' => []
                ]
            ]
        ]
    ]
);
