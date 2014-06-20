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
use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\structure\model\object\SystemPage;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE_HIERARCHIC,
    'class' => 'umicms\project\module\structure\model\collection\StructureElementCollection',

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