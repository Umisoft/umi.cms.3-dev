<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Subscription;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_subscription'
        ],
        'fields'     => [
            Subscription::FIELD_DISPATCH   => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'dispatch_id',
                'target'     => 'dispatch'
            ],
            Subscription::FIELD_SUBSCRIBER => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'subscriber_id',
                'target'     => 'dispatchSubscriber'
            ],
            Subscription::FIELD_TOKEN => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'token',
            ],

        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Subscription',
                'fields'      => [
                    Subscription::FIELD_DISPATCH   => [],
                    Subscription::FIELD_SUBSCRIBER => [],
                    Subscription::FIELD_TOKEN      => []
                ]
            ]
        ]
    ]
);