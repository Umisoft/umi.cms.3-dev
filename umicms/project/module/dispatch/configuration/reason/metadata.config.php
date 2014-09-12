<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatch\model\object\Reason;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatch_reason'
        ],
        'fields'      => [
            Reason::FIELD_RELEASE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'release_id',
                'target' => 'release'
            ],
            Reason::FIELD_SUBSCRIBER => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'subscriber_id',
                'target' => 'subscriber'
            ],
            Reason::FIELD_DATE_UNSUBSCIBE => [
                'type' => IField::TYPE_DATE_TIME,
                'columnName' => 'date_unsubscribe',
            ],
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\Reason',
                'fields'      => [
                    Reason::FIELD_RELEASE => [],
                    Reason::FIELD_SUBSCRIBER => [],
                    Reason::FIELD_DATE_UNSUBSCIBE => [],
                ]
            ]
        ],
    ]
);
