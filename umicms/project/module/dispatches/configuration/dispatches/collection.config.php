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
use umi\orm\metadata\IObjectType;
use umicms\project\module\dispatches\model\collection\DispatchesCollection;
use umicms\project\module\dispatches\model\object\Dispatches;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\dispatches\model\collection\DispatchesCollection',
    'handlers' => [
        'admin' => 'dispatches.dispatches',
        //'site' => 'dispatches.dispatches'
    ],
    'forms' => [
        'base' => [
            DispatchesCollection::FORM_EDIT => '{#lazy:~/project/module/dispatches/configuration/dispatches/form/base.edit.config.php}',
            DispatchesCollection::FORM_CREATE => '{#lazy:~/project/module/dispatches/configuration/dispatches/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.dispatches', 'collection'
    ]
];