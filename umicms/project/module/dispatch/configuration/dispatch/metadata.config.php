<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatch\model\object\Dispatch;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatch'
        ],
		'fields' => [
		/* 	Dispatch::FIELD_NEWS_LENT => [
				'type' => IField::TYPE_BELONGS_TO,
				'columnName' => 'news_lent',
				'target' => 'newsLent'
			], */
			/*Dispatch::FIELD_GROUP_USER => [
                'type'         => IField::TYPE_HAS_MANY,
                'target'       => Dispatch::FIELD_GROUP_USER,
                'targetField'  => 'userGroup'
            ],*/
            Dispatch::FIELD_SUBSCRIBER => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'subscriber',
                'bridge' => 'subscribersDispatches',
                'relatedField' => 'dispatch',
                'targetField' => 'subscriber'
            ],
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatch\model\object\Dispatch',
                'fields'      => [
					Dispatch::FIELD_SUBSCRIBER => [],
                    //Dispatch::FIELD_GROUP_USER => []
                    //Dispatch::FIELD_UN_SUBSCRIBER => []
				]
            ]
        ],
    ]
);
