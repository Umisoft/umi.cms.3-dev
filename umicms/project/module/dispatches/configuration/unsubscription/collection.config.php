<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\collection\ICollectionFactory;

use umicms\project\module\dispatches\model\collection\UnsubscriptionCollection;
use umicms\project\module\dispatches\model\object\Unsubscription;

return [
    'type'     => ICollectionFactory::TYPE_SIMPLE,
    'class'        => 'umicms\project\module\dispatches\model\collection\UnsubscriptionCollection',
    'handlers' => [
        'admin' => 'dispatches.unsubscription'
    ],
    'dictionaries' => [
        'collection.dispatchUnsubscription',
        'collection' => 'collection'
    ],

    UnsubscriptionCollection::IGNORED_TABLE_FILTER_FIELDS => [
        Unsubscription::FIELD_DISPLAY_NAME => []
    ],

    UnsubscriptionCollection::DEFAULT_TABLE_FILTER_FIELDS => [
        Unsubscription::FIELD_REASON => [],
    ]
];