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
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'handlers' => [
        'admin' => 'structure.page',
        'site' => 'structure'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/orm/structure/form/base.edit.config.php}'
        ],
        'static' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/orm/structure/form/static.edit.config.php}'
        ],
        'system' => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/orm/structure/form/system.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection\structure', 'collection'
    ]
];