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
use umicms\project\module\blog\model\collection\BlogAuthorCollection;
use umicms\project\module\blog\model\object\BlogAuthor;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/collection/page.common.config.php',
    [
        'type' => ICollectionFactory::TYPE_SIMPLE,
        'class' => 'umicms\project\module\blog\model\collection\BlogAuthorCollection',
        'handlers' => [
            'admin' => 'blog.author',
            'site' => 'blog.author.view'
        ],
        'forms' => [
            'base' => [
                BlogAuthorCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/author/form/base.edit.config.php}',
                BlogAuthorCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/author/form/base.create.config.php}',
                BlogAuthor::FORM_EDIT_PROFILE => '{#lazy:~/project/module/blog/site/author/profile/form/base.editAuthor.config.php}'
            ]
        ],
        'dictionaries' => [
            'collection.blogAuthor' => 'collection.blogAuthor', 'collection' => 'collection'
        ],
        BlogAuthorCollection::DEFAULT_TABLE_FILTER_FIELDS => [
            BlogAuthor::FIELD_PROFILE => []
        ]
    ]
);