<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\blog\api\object\BlogPost;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\blog\api\collection\BlogPostCollection',
    'handlers' => [
        'admin' => 'blog.post',
        'site' => 'blog.post'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/post/form/base.edit.config.php}',
            BlogPost::FORM_ADD_POST => '{#lazy:~/project/module/blog/site/post/form/base.addPost.config.php}',
            BlogPost::FORM_EDIT_POST => '{#lazy:~/project/module/blog/site/post/form/base.editPost.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.blogPost', 'collection'
    ]
];