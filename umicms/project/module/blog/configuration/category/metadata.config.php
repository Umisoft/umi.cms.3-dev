<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umi\orm\metadata\IObjectType;
use umicms\project\Environment;
use umicms\project\module\blog\model\object\BlogCategory;
use umicms\project\module\blog\model\object\BlogPost;


return array_replace_recursive(
    require Environment::$directoryCmsProject . '/configuration/model/metadata/hierarchicPageCollection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'blog_category'
        ],
        'fields' => [
            BlogCategory::FIELD_POSTS => [
                'type' => IField::TYPE_HAS_MANY,
                'target' => 'blogPost',
                'targetField' => BlogPost::FIELD_CATEGORY
            ]
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\blog\model\object\BlogCategory',
                'fields' => [
                    BlogCategory::FIELD_POSTS => []
                ]
            ]
        ]
    ]
);
