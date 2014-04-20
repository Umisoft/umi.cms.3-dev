<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\blog\api\object\BlogComment;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\blog\api\collection\BlogCommentCollection',
    'handlers' => [
        'admin' => 'blog.comment',
        'site' => 'blog.comment'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/comment/form/base.edit.config.php}',
            BlogComment::FORM_ADD_COMMENT => '{#lazy:~/project/module/blog/site/comment/form/base.addComment.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.blogComment', 'collection'
    ]
];