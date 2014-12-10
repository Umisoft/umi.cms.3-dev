<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\forum\model\collection\ForumAuthorCollection;
use umicms\project\module\forum\model\object\ForumAuthor;


return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/collection/page.common.config.php',
    [
        'type' => ICollectionFactory::TYPE_SIMPLE,
        'class' => 'umicms\project\module\forum\model\collection\ForumAuthorCollection',
        'handlers' => [
            'admin' => 'forum.author',
            'site' => 'forum.author.view'
        ],
        'forms' => [
            'base' => [
                ForumAuthorCollection::FORM_EDIT => '{#lazy:~/project/module/forum/configuration/author/form/base.edit.config.php}',
                ForumAuthorCollection::FORM_CREATE => '{#lazy:~/project/module/forum/configuration/author/form/base.create.config.php}',
            ]
        ],
        'dictionaries' => [
            'collection.forumAuthor' => 'collection.forumAuthor', 'collection' => 'collection'
        ],
        ForumAuthorCollection::DEFAULT_TABLE_FILTER_FIELDS => [
            ForumAuthor::FIELD_USER => []
        ]
    ]
);