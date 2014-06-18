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
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\blog\model\object\BlogPost;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\blog\model\collection\BlogPostCollection',
    'handlers' => [
        ICmsCollection::HANDLER_ADMIN => 'blog.post',
        ICmsCollection::HANDLER_SITE => 'blog.post',
        BlogPostCollection::HANDLER_DRAFT => 'blog.draft.view',
        BlogPostCollection::HANDLER_MODERATE => 'blog.moderate.view',
        BlogPostCollection::HANDLER_REJECT => 'blog.reject'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/post/form/base.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/post/form/base.create.config.php}',
            BlogPost::FORM_ADD_POST => '{#lazy:~/project/module/blog/site/post/form/base.addPost.config.php}',
            BlogPost::FORM_EDIT_POST => '{#lazy:~/project/module/blog/site/post/form/base.editPost.config.php}',
            BlogPost::FORM_PUBLISH_POST => '{#lazy:~/project/module/blog/site/post/form/base.publish.config.php}',
            BlogPost::FORM_MODERATE_POST => '{#lazy:~/project/module/blog/site/post/form/base.moderate.config.php}',
            BlogPost::FORM_REJECT_POST => '{#lazy:~/project/module/blog/site/post/form/base.reject.config.php}',
            BlogPost::FORM_DRAFT_POST => '{#lazy:~/project/module/blog/site/post/form/base.draft.config.php}',
        ]
    ],
    'dictionaries' => [
        'collection.blogPost', 'collection'
    ]
];