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

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/scheme/collection.config.php',
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
                'type' => Type::STRING
            ],
            'phone_number_en' => [
                'type' => Type::STRING
            ],
            'email' => [
                'type' => Type::STRING
            ],
            'email_en' => [
                'type' => Type::STRING
            ],
            'address' => [
                'type' => Type::TEXT
            ],
            'address_en' => [
                'type' => Type::TEXT
            ],
            'logo' => [
                'type' => Type::TEXT
            ],
            'logo_en' => [
                'type' => Type::TEXT
            ],
            'copyright' => [
                'type' => Type::TEXT
            ],
            'copyright_en' => [
                'type' => Type::TEXT
            ],
            'counter' => [
                'type' => Type::TEXT
            ],
            'counter_en' => [
                'type' => Type::TEXT
            ],
            'widget_vk' => [
                'type' => Type::TEXT
            ],
            'widget_vk_en' => [
                'type' => Type::TEXT
            ],
            'widget_facebook' => [
                'type' => Type::TEXT
            ],
            'widget_facebook_en' => [
                'type' => Type::TEXT
            ],
            'widget_twitter' => [
                'type' => Type::TEXT
            ],
            'widget_twitter_en' => [
                'type' => Type::TEXT
            ],
            'share' => [
                'type' => Type::TEXT
            ],
            'share_en' => [
                'type' => Type::TEXT
            ],
            'social_group_link' => [
                'type' => Type::TEXT
            ],
            'social_group_link_en' => [
                'type' => Type::TEXT
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
