<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\structure\api\object\Menu;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\structure\api\collection\MenuCollection',

    'handlers' => [
        'admin' => 'structure.menu',
        'site' => 'structure.menu'
    ],
    'forms' => [
        Menu::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menu.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menu.create.config.php}'
        ],
    ],
    'dictionaries' => [
        'collection.menu', 'collection'
    ]
];