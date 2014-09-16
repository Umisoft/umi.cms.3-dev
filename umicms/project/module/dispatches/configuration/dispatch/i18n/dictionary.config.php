<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use umicms\project\module\dispatches\model\object\Dispatch;

return [
		'en-US' => [
			Dispatch::FIELD_DESCRIPTION => 'Description',
			Dispatch::FIELD_DATE_LAST_SENDING => 'Last date of sending letters',
			Dispatch::FIELD_SUBSCRIBER => 'Subscribers',
			
			'type:base:displayName' => 'Dispatch'
		],

		'ru-RU' => [
			Dispatch::FIELD_DESCRIPTION => 'Описание',
			Dispatch::FIELD_DATE_LAST_SENDING => 'Дата последней отправки писем',
			Dispatch::FIELD_SUBSCRIBER => 'Подписчики',

			'type:base:displayName' => 'Рассылка'
		]
];
 