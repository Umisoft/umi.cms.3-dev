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
use umicms\project\module\forum\model\collection\ForumMessageCollection;
use umicms\project\module\forum\model\object\ForumBranchMessage;
use umicms\project\module\forum\model\object\ForumMessage;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\forum\model\collection\ForumMessageCollection',
    'handlers' => [
        'admin' => 'forum.message',
        'site' => 'forum.message'
    ],
    'forms' => [
        ForumMessage::TYPE_NAME => [
            ForumMessageCollection::FORM_EDIT => '{#lazy:~/project/module/forum/configuration/message/form/comment.edit.config.php}',
            ForumMessageCollection::FORM_CREATE => '{#lazy:~/project/module/forum/configuration/message/form/comment.create.config.php}',
        ],
        ForumBranchMessage::TYPE_NAME => [
            ForumMessageCollection::FORM_EDIT => '{#lazy:~/project/module/forum/configuration/message/form/branchComment.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.forumMessage' => 'collection.forumMessage', 'collection' => 'collection'
    ]
];
