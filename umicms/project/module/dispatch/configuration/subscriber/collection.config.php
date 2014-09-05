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
use umicms\project\module\dispatch\model\collection\SubscriberCollection;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\BaseSubscriber;

return [
    'type' => ICollectionFactory::TYPE_SIMPLE,
    'class' => 'umicms\project\module\dispatch\model\collection\SubscriberCollection',
    'handlers' => [
        'admin' => 'dispatch.subscriber',
        'site' => 'dispatch.subscriber'
    ],
    'forms' => [
		/* IObjectType::BASE => [
            SubscriberCollection::FORM_EDIT => '{#lazy:~/project/module/dispatch/configuration/subscriber/form/base.edit.config.php}'
            SubscriberCollection::FORM_CREATE => '{#lazy:~/project/module/dispatch/configuration/subscriber/form/base.create.config.php}'
        ], */
        IObjectType::BASE => [
            SubscriberCollection::FORM_EDIT => '{#lazy:~/project/module/dispatch/configuration/subscriber/form/base.edit.config.php}',
            SubscriberCollection::FORM_CREATE => '{#lazy:~/project/module/dispatch/configuration/subscriber/form/base.create.config.php}'
        ],
        Subscriber::TYPE_NAME => [
            Subscriber::FORM_SUBSCRIBE_SITE => '{#lazy:~/project/module/dispatch/configuration/subscriber/form/subscriber.site.config.php}'
        ],
    ],
    'dictionaries' => [
        'collection.subscriber', 'collection'
    ],
	SubscriberCollection::IGNORED_TABLE_FILTER_FIELDS => [
        BaseSubscriber::FIELD_DISPATCH => [],
        BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => []
    ],
];