<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\dispatches\model\collection\DispatchCollection;

return [
    'type'         => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatches\model\collection\DispatchCollection',
    'handlers'     => [
        'admin' => 'dispatches.dispatch',
        'site'  => 'dispatches.dispatch'
    ],
    'forms'        => [
        'base' => [
            DispatchCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatches/configuration/dispatch/form/base.edit.config.php}',
            DispatchCollection::FORM_CREATE => '{#lazy:~/project/module/dispatches/configuration/dispatch/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.dispatch',
        'collection'
    ]
];