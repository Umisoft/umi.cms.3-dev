<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'handlers' => [
        'admin' => 'news.subject',
        'site' => 'news.subject'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/news/configuration/subject/form/base.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection\newsSubject', 'collection'
    ]
];