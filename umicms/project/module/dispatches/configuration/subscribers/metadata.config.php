<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\filter\IFilterFactory;
use umi\orm\metadata\field\IField;
use umi\validation\IValidatorFactory;
use umicms\project\module\dispatches\model\object\Subscribers;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource'	=> [
            'sourceName'	=> 'dispatches_subscribers'
        ],
		'fields'	=> [
            Subscribers::FIELD_EMAIL	=> [
                'type'       => IField::TYPE_STRING,
                'columnName' => 'email',
                'filters'    => [
                    IFilterFactory::TYPE_STRING_TRIM	=> [],
                    IFilterFactory::TYPE_STRIP_TAGS		=> []
                ],
                'validators' => [
                    IValidatorFactory::TYPE_REQUIRED	=> [],
                    IValidatorFactory::TYPE_EMAIL		=> [],
                ]
            ],
			Subscribers::FIELD_SEX		=> [
                'type'       => IField::TYPE_BELONGS_TO,
				'columnName' => 'sex_id',
				//'target'     => 'sex'
            ],
			Subscribers::FIELD_DISPATCHES => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatches',
                'bridge' => 'subscribersDispatches',
                'relatedField' => 'subscribers',
                'targetField' => 'dispatches',
				'validators' => [
                    IValidatorFactory::TYPE_REQUIRED	=> [],
                ]
            ],
			Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'dispatches',
                'bridge' => 'unsubscribeDispatches',
                'relatedField' => 'subscribers',
                'targetField' => 'dispatches'
            ],
        ],
        'types'	=> [
            'base'	=> [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Subscribers',
                'fields'      => [
					Subscribers::FIELD_EMAIL		=>	[],
					Subscribers::FIELD_SEX			=>	[],
					Subscribers::FIELD_DISPATCHES	=>	[],
					Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES	=>	[],
				]
            ]
        ],
    ]
);
