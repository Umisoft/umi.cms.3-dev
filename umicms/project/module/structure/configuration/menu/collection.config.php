<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\structure\model\collection\MenuCollection;
use umicms\project\module\structure\model\object\Menu;
use umicms\project\module\structure\model\object\MenuExternalItem;
use umicms\project\module\structure\model\object\MenuInternalItem;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\structure\model\collection\MenuCollection',

    'handlers' => [
        'admin' => 'structure.menu',
        'site' => 'structure.menu'
    ],
    'forms' => [
        Menu::TYPE => [
            MenuCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menu.edit.config.php}',
            MenuCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menu.create.config.php}'
        ],
        MenuInternalItem::TYPE => [
            MenuCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menuInternal.edit.config.php}',
            MenuCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menuInternal.create.config.php}'
        ],
        MenuExternalItem::TYPE => [
            MenuCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menuExternal.edit.config.php}',
            MenuCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menuExternal.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.menu', 'collection'
    ],

    MenuCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        MenuExternalItem::FIELD_RESOURCE_URL
    ]
];