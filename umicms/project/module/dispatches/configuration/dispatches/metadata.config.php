<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\orm\metadata\field\IField;
use umicms\project\module\dispatches\model\object\Dispatches;

return array_replace_recursive(
    require CMS_PROJECT_DIR . '/configuration/model/metadata/collection.config.php',
    [
        'dataSource' => [
            'sourceName' => 'dispatches'
        ],
		'fields' => [
		/* 	Dispatches::FIELD_NEWS_LENT => [
				'type' => IField::TYPE_BELONGS_TO,
				'columnName' => 'news_lent',
				'target' => 'newsLent'
			], */
			Dispatches::FIELD_SUBSCRIBERS => [
                'type' => IField::TYPE_MANY_TO_MANY,
                'target' => 'subscribers',
                'bridge' => 'subscribersDispatches',
                'relatedField' => 'dispatches',
                'targetField' => 'subscribers'
            ],
        ],
        'types'  => [
            'base' => [
                'objectClass' => 'umicms\project\module\dispatches\model\object\Dispatches',
                'fields'      => [
					//Dispatches::FIELD_SUBSCRIBERS => []
				]
            ]
        ],
    ]
);
