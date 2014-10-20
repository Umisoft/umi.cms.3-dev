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
use umicms\project\module\dispatches\model\object\Subscriber;
use umi\orm\metadata\IObjectType;

return [
    'type'         => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatches\model\collection\SubscriberCollection',
    'handlers'     => [
        'admin' => 'dispatches.subscriber',
        'site' => 'dispatches.subscriber'
    ],
    'forms'        => [
        IObjectType::BASE      => [
            SubscriberCollection::FORM_EDIT   => '{#lazy:~/project/module/dispatches/configuration/subscriber/form/base.edit.config.php}',
            Subscriber::FORM_SUBSCRIBE_SITE   => '{#lazy:~/project/module/dispatches/site/subscription/form/subscribe.config.php}',
            Subscriber::FORM_UNSUBSCRIBE_SITE   => '{#lazy:~/project/module/dispatches/site/unsubscription/form/unsubscribe.config.php}',
        ],
    ],
    'dictionaries' => [
        'collection.dispatchSubscriber',
        'collection'
    ]
];