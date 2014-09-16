<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_subscription'
        ],
        'fields'     => [
            'dispatch'   => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'dispatch_id',
                'target'     => 'dispatch'
            ],
            'subscriber' => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'subscriber_id',
                'target'     => 'dispatchSubscriber'
            ]
        ],
        'types'      => [
            'base' => [
                'objectClass' => 'umicms\orm\object\CmsLinkObject',
                'fields'      => [
                    'dispatch'   => [],
                    'subscriber' => []
                ]
            ]
        ]
    ]
);