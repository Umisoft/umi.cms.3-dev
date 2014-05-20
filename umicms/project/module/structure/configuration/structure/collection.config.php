<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\structure\api\object\StaticPage;
use umicms\project\module\structure\api\object\SystemPage;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\structure\api\collection\StructureElementCollection',

    'handlers' => [
        'admin' => 'structure.page',
        'site' => 'structure'
    ],
    'forms' => [
        StaticPage::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/structure/form/static.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/structure/form/static.create.config.php}'
        ],
        SystemPage::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/structure/form/system.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.structure', 'collection'
    ]
];