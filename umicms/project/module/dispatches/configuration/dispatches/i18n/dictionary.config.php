<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use umicms\project\module\dispatches\model\object\Dispatches;

return [
		'en-US' => [
			Dispatches::FIELD_DESCRIPTION => 'Description',
			Dispatches::FIELD_DATE_LAST_SENDING => 'Last date of sending letters',
			Dispatches::FIELD_SUBSCRIBERS => 'Subscribers',
			
			'type:base:displayName' => 'Dispatch'
		],

		'ru-RU' => [
			Dispatches::FIELD_DESCRIPTION => 'Описание',
			Dispatches::FIELD_DATE_LAST_SENDING => 'Дата последней отправки писем',
			Dispatches::FIELD_SUBSCRIBERS => 'Подписчики',

			'type:base:displayName' => 'Рассылка'
		]
];
 