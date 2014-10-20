<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\IObjectType;
use umi\orm\metadata\field\IField;
use umi\filter\IFilterFactory;
use umi\validation\IValidatorFactory;
use umicms\project\module\dispatches\model\object\Subscriber;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    require CMS_PROJECT_DIR . '/configuration/model/metadata/userAssociated.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches_subscriber'
        ],
        'fields' => [
            Subscriber::FIELD_EMAIL => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'email',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => [],
                    IValidatorFactory::TYPE_EMAIL => [],
                ]
            ],
            Subscriber::FIELD_FIRST_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'first_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
            ],
            Subscriber::FIELD_MIDDLE_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'middle_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
            ],
            Subscriber::FIELD_LAST_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'last_name',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM => [],
                    IFilterFactory::TYPE_STRIP_TAGS => []
                ],
            ],
            Subscriber::FIELD_DISPATCHES => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatch',
                'bridge' => 'dispatchSubscription',
                'relatedField' => 'subscriber',
                'targetField' => 'dispatch'
            ],
            Subscriber::FIELD_UNSUBSCRIBED_DISPATCHES => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatch',
                'bridge' => 'dispatchUnsubscription',
                'relatedField' => 'subscriber',
                'targetField' => 'dispatch'
            ],
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Subscriber',
                'fields'      => [
                    Subscriber::FIELD_EMAIL => [],
                    Subscriber::FIELD_FIRST_NAME => [],
                    Subscriber::FIELD_MIDDLE_NAME => [],
                    Subscriber::FIELD_LAST_NAME => [],
                    Subscriber::FIELD_DISPATCHES => [],
                    Subscriber::FIELD_UNSUBSCRIBED_DISPATCHES => [],
                ]
            ],
        ],
    ]
);
