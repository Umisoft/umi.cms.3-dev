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
use umicms\project\module\dispatches\model\collection\SubscribersCollection;
use umicms\project\module\dispatches\model\object\Subscribers;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\dispatches\model\collection\SubscribersCollection',
    'handlers' => [
        'admin' => 'dispatches.subscribers',
        //'site' => 'dispatches.dispatches'
    ],
    'forms' => [
        'base' => [
            SubscribersCollection::FORM_EDIT => '{#lazy:~/project/module/dispatches/configuration/subscribers/form/base.edit.config.php}',
            SubscribersCollection::FORM_CREATE => '{#lazy:~/project/module/dispatches/configuration/subscribers/form/base.create.config.php}'
        ]
    ],
    'dictionaries' => [
        'collection.subscribers', 'collection'
    ]
];