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
use umicms\project\module\dispatch\model\object\BaseSubscriber;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\SubscriberUser;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatch_subscriber'
        ],
        'fields' => [
            BaseSubscriber::FIELD_EMAIL => [
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
            BaseSubscriber::FIELD_FIRST_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'first_name',
            ],
            BaseSubscriber::FIELD_MIDDLE_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'middle_name',
            ],
            BaseSubscriber::FIELD_LAST_NAME => [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'last_name',
            ],
            BaseSubscriber::FIELD_SEX => [
                'type'       => IField::TYPE_BELONGS_TO,
                'columnName' => 'sex_id',
                //'target'     => 'sex'
            ],
            BaseSubscriber::FIELD_DISPATCH => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatch',
                'bridge' => 'subscribersDispatches',
                'relatedField' => 'subscriber',
                'targetField' => 'dispatch',
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED => [],
                ]
            ],
            /*BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatch',
                'bridge' => 'unsubscribeDispatches',
                'relatedField' => 'subscriber',
                'targetField' => 'dispatch'
            ],*/
            BaseSubscriber::FIELD_PROFILE => [
                'type' => IField::TYPE_BELONGS_TO,
                'columnName' => 'profile_id',
                'target' => 'user'
            ],
        ],
        'types' => [
            IObjectType::BASE => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\BaseSubscriber',
                'fields'      => [
                    BaseSubscriber::FIELD_EMAIL => [],
                    BaseSubscriber::FIELD_FIRST_NAME => [],
                    BaseSubscriber::FIELD_MIDDLE_NAME => [],
                    BaseSubscriber::FIELD_LAST_NAME => [],
                    BaseSubscriber::FIELD_SEX => [],
                    BaseSubscriber::FIELD_DISPATCH => [],
                    //BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => [],
                ]
            ],
            Subscriber::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\Subscriber',
                'fields'      => []
            ],
            SubscriberUser::TYPE_NAME => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\SubscriberUser',
                'fields'      => [
                    BaseSubscriber::FIELD_PROFILE => [],
                ]
            ],
        ],
    ]
);
