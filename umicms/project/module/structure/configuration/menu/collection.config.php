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
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menu.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menu.create.config.php}'
        ],
        MenuInternalItem::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menuInternal.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menuInternal.create.config.php}'
        ],
        MenuExternalItem::TYPE => [
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/menu/form/menuExternal.edit.config.php}',
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/menu/form/menuExternal.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.menu', 'collection'
    ]
];