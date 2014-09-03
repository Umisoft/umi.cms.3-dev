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

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/collection/page.common.config.php',
    [
        'type' => ICollectionFactory::TYPE_SIMPLE,
        'class' => 'umicms\project\module\blog\model\collection\BlogTagCollection',
        'handlers' => [
            'admin' => 'blog.tag',
            'site' => 'blog.tag'
        ],
        'forms' => [
            'base' => [
                ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/blog/configuration/tag/form/base.edit.config.php}',
                ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/blog/configuration/tag/form/base.create.config.php}'
            ]
        ],
        'dictionaries' => [
            'collection.blogTag' => 'collection.blogTag', 'collection' => 'collection'
        ]
    ]
);