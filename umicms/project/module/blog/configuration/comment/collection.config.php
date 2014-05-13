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
        'comment' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/comment/form/comment.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/comment/form/comment.create.config.php}',
            BlogComment::FORM_ADD_COMMENT => '{#lazy:~/project/module/blog/site/comment/form/comment.addComment.config.php}',
            BlogComment::FORM_CHANGE_COMMENT_STATUS => '{#lazy:~/project/module/blog/site/comment/form/comment.changeStatus.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.blogComment', 'collection'
    ]
];