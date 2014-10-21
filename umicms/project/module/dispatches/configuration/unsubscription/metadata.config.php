<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Unsubscription;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_unsubscription'
        ],
        'fields' => [
            Unsubscription::FIELD_DISPATCH => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'dispatch_id',
                'target' => 'dispatch'
            ],
            Unsubscription::FIELD_SUBSCRIBER => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'subscriber_id',
                'target' => 'dispatchSubscriber'
            ],
            Unsubscription::FIELD_RELEASE    => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'release_id',
                'target'     => 'dispatchRelease'
            ],
            Unsubscription::FIELD_REASON    => [
                'type'       => IField::TYPE_TEXT,
                'columnName' => 'reason_unsubscribe',
            ],
        ],
        'types' => [
            'base' => [
                'objectClass' => 'umicms\orm\object\CmsLinkObject',
                'fields' => [
                    Unsubscription::FIELD_DISPATCH  => [],
                    Unsubscription::FIELD_SUBSCRIBER  => [],
                    Unsubscription::FIELD_RELEASE => [],
                    Unsubscription::FIELD_REASON => []
                ]
            ]
        ]
    ]
);