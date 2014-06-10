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
use umicms\project\module\blog\api\object\BlogAuthor;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\blog\api\collection\BlogAuthorCollection',
    'handlers' => [
        'admin' => 'blog.author',
        'site' => 'blog.author'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/author/form/base.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/author/form/base.create.config.php}',
            BlogAuthor::FORM_EDIT_PROFILE => '{#lazy:~/project/module/blog/site/author/form/base.editAuthor.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.blogAuthor', 'collection'
    ]
];