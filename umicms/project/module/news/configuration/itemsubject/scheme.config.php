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
    require Environment::$directoryCmsProject . '/configuration/scheme/collection.config.php',
    [
        'columns'     =>  [
            'display_name_en' => [
                'type'    => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'contents_en'     => [
                'type' => Type::TEXT
            ],
            'date'            => [
                'type' => Type::DATETIME
            ],
            'announcement'    => [
                'type' => Type::TEXT
            ],
            'announcement_en' => [
                'type' => Type::TEXT
            ],
            'source' => [
                'type' => Type::STRING
            ],
            'rubric_id' => [
                'type' => Type::BIGINT,
                'options' => [
                    'unsigned' => true,
                    'notnull' => false
                ]
            ]
        ],
        'indexes'     => [],
        'constraints' => [],
        'options'     => []
    ]
);
