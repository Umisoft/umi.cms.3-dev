<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Logdispatch;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_log_dispatch'
        ],
        'fields'     => [
            Logdispatch::FIELD_RELEASE             => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'release_id',
                'target'     => 'dispatchRelease'
            ],
            Logdispatch::FIELD_SUBSCRIBERS             => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'subscriber_id',
                'target'     => 'dispatchSubscriber'
            ],
            Logdispatch::FIELD_READ             => [
                'type'       => IField::TYPE_BOOL,
                'columnName' => 'read',
            ],
            Logdispatch::FIELD_SENT             => [
                'type'       => IField::TYPE_BOOL,
                'columnName' => 'sent',
            ],
        ],
                'types'      => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Release',
                'fields'      => [
                    Logdispatch::FIELD_RELEASE      => [],
                    Logdispatch::FIELD_SUBSCRIBERS  => [],
                    Logdispatch::FIELD_READ         => [],
                    Logdispatch::FIELD_SENT         => [],
                ]
            ]
        ],
    ]
);
