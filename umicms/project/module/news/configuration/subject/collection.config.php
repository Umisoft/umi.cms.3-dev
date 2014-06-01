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

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\news\api\collection\NewsSubjectCollection',
    'handlers' => [
        'admin' => 'news.subject',
        'site' => 'news.subject'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/news/configuration/subject/form/base.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/news/configuration/subject/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.newsSubject', 'collection'
    ]
];