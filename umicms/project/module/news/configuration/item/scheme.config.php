<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use Doctrine\DBAL\Types\Type;
use umicms\project\Environment;

return array_merge_recursive(
    require Environment::$directoryCmsProject . '/configuration/scheme/page.config.php',
    [
        'name'        => 'project_news_item',
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
