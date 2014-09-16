<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Dispatch;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatch'
        ],
        'fields' => [
            Dispatch::FIELD_GROUP_USER => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'userGroup',
                'bridge' => 'dispatchUserGroup',
                'relatedField' => 'dispatch',
                'targetField' => 'userGroup'
            ],
            Dispatch::FIELD_SUBSCRIBER => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatchSubscriber',
                'bridge' => 'dispatchSubscription',
                'relatedField' => 'dispatch',
                'targetField' => 'subscriber'
            ],
            /*Dispatch::FIELD_UNSUBSCRIBER => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatchSubscriber',
                'bridge' => 'dispatchUnsubscription',
                'relatedField' => 'dispatch',
                'targetField' => 'unsubscriber'
            ],*/
            Dispatch::FIELD_RELEASE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'release_id',
                'target' => 'dispatchRelease'
            ],
            Dispatch::FIELD_DATE_LAST_SENDING => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'date_last_sending',
            ],
            Dispatch::FIELD_DESCRIPTION => [
                'type' => IField::TYPE_TEXT,
                'columnName' => 'description',
            ],
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Dispatch',
                'fields'      => [
                    Dispatch::FIELD_RELEASE => [],
                    Dispatch::FIELD_SUBSCRIBER => [],
                    Dispatch::FIELD_GROUP_USER => [],
                    //Dispatch::FIELD_UNSUBSCRIBER => [],
                    Dispatch::FIELD_DATE_LAST_SENDING => [],
                    Dispatch::FIELD_DESCRIPTION => [],
                ]
            ]
        ],
    ]
);
