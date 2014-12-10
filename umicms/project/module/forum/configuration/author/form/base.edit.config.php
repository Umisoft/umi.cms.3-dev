<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\form\element\Select;
use umicms\project\module\forum\model\object\ForumAuthor;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/form/page.base.edit.config.php',
    [
        'options' => [
            'dictionaries' => [
                'collection.forumAuthor' => 'collection.forumAuthor'
            ]
        ],
        'elements' => [
            'contents' => [
                'elements' => [
                    ForumAuthor::FIELD_USER => [
                        'type' => Select::TYPE_NAME,
                        'label' => ForumAuthor::FIELD_USER,
                        'options' => [
                            'lazy' => true,
                            'dataSource' => ForumAuthor::FIELD_USER
                        ]
                    ],
                    ForumAuthor::FIELD_PAGE_CONTENTS => [
                        'options' => [
                            'dataSource' => ForumAuthor::FIELD_PAGE_CONTENTS_RAW
                        ]
                    ]
                ]
            ]
        ]
    ]
);