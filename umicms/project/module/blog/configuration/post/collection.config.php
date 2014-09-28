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
use umi\orm\metadata\IObjectType;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\blog\model\object\BlogPost;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/collection/page.common.config.php',
    [
        'type' => ICollectionFactory::TYPE_SIMPLE,
        'class' => 'umicms\project\module\blog\model\collection\BlogPostCollection',
        'handlers' => [
            BlogPostCollection::HANDLER_ADMIN => 'blog.post',
            BlogPostCollection::HANDLER_SITE => 'blog.post.view',
            BlogPostCollection::HANDLER_DRAFT => 'blog.draft.view',
            BlogPostCollection::HANDLER_MODERATE_OWN => 'blog.moderate.own',
            BlogPostCollection::HANDLER_MODERATE_ALL => 'blog.moderate.all',
            BlogPostCollection::HANDLER_REJECT => 'blog.reject.view'
        ],
        'forms' => [
            IObjectType::BASE => [
                BlogPostCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/post/form/post.edit.config.php}',
                BlogPostCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/post/form/post.create.config.php}',
                BlogPost::FORM_ADD_POST => '{#lazy:~/project/module/blog/site/post/add/form/post.add.config.php}',
                BlogPost::FORM_ADD_VISITOR_POST => '{#lazy:~/project/module/blog/site/post/add/form/post.visitorAdd.config.php}',
                BlogPost::FORM_EDIT_POST => '{#lazy:~/project/module/blog/site/form/base.edit.config.php}',
                BlogPost::FORM_PUBLISH_POST => '{#lazy:~/project/module/blog/site/form/base.publish.config.php}',
                BlogPost::FORM_MODERATE_POST => '{#lazy:~/project/module/blog/site/form/base.moderate.config.php}',
                BlogPost::FORM_REJECT_POST => '{#lazy:~/project/module/blog/site/form/base.reject.config.php}',
                BlogPost::FORM_DRAFT_POST => '{#lazy:~/project/module/blog/site/form/base.draft.config.php}',
            ]
        ],
        'dictionaries' => [
            'collection.blogPost' => 'collection.blogPost', 'collection' => 'collection'
        ],

        BlogPostCollection::DEFAULT_TABLE_FILTER_FIELDS => [
            BlogPost::FIELD_CATEGORY => [],
            BlogPost::FIELD_AUTHOR => [],
            BlogPost::FIELD_PUBLISH_TIME => [],
            BlogPost::FIELD_STATUS => []
        ]
    ]
);