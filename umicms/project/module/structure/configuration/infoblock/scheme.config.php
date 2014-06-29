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
    [
        'name' => 'infoblock',
        'columns'     =>  [
            'name' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => true
                ]
            ],
            'phone_number' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'phone_number_en' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'email' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'email_en' => [
                'type' => Type::STRING,
                'options' => [
                    'notnull' => false
                ]
            ],
            'address' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'address_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'logo' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'logo_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'copyright' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'copyright_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'counter' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'counter_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_vk' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_vk_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_facebook' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_facebook_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_twitter' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'widget_twitter_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'share' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'share_en' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'social_group_link' => [
                'type' => Type::TEXT,
                'options' => [
                    'notnull' => false
                ]
            ],
            'social_group_link_en' => [
                'type' => Type::TEXT,
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
