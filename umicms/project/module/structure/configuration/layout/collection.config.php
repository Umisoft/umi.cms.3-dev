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
use umicms\project\module\structure\model\collection\LayoutCollection;
use umicms\project\module\structure\model\object\Layout;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\structure\model\collection\LayoutCollection',
    'handlers' => [
        'admin' => 'structure.layout'
    ],
    'dictionaries' => [
        'collection.layout', 'collection'
    ],
    'forms' => [
        'base' => [
            LayoutCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/layout/form/base.edit.config.php}',
            LayoutCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/layout/form/base.create.config.php}'
        ]
    ],

    LayoutCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        Layout::FIELD_FILE_NAME => []
    ]
];