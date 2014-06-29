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
    'class' => 'umicms\project\module\structure\model\collection\RobotsCollection',
    'handlers' => [
        'admin' => 'service.robots'
    ],
    'forms' => [
        'base' => [
            ICmsCollection::FORM_CREATE => '{#lazy:~/project/module/structure/configuration/robots/form/base.create.config.php}',
            ICmsCollection::FORM_EDIT => '{#lazy:~/project/module/structure/configuration/robots/form/base.edit.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.robots', 'collection'
    ]
];