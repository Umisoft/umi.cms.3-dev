<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;
use umicms\project\module\dispatches\model\collection\SubscriberCollection;
use umicms\project\module\dispatches\model\object\GuestSubscriber;
use umicms\project\module\dispatches\model\object\RegisteredSubscriber;

return [
    'type'         => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatches\model\collection\SubscriberCollection',
    'handlers'     => [
        'admin' => 'dispatches.subscriber'
    ],
    'forms'        => [
        GuestSubscriber::TYPE_NAME      => [
            SubscriberCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatches/configuration/subscriber/form/base.edit.config.php}',
        ],
        RegisteredSubscriber::TYPE_NAME => [
            SubscriberCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatches/configuration/subscriber/form/base.edit.config.php}',
        ]
    ],
    'dictionaries' => [
        'collection.dispatchSubscriber',
        'collection'
    ]
];