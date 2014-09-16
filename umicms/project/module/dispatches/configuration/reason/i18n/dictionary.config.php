<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use umicms\project\module\dispatches\model\object\Reason;

return [
		'en-US' => [
			Reason::FIELD_RELEASE => 'Release',
			Reason::FIELD_SUBSCRIBER => 'Subscriber',
			Reason::FIELD_DATE_UNSUBSCIBE => 'Date unsubscribe',
		],

		'ru-RU' => [
			Reason::FIELD_RELEASE => 'Выпуск рассылки',
			Reason::FIELD_SUBSCRIBER => 'Подписчик',
			Reason::FIELD_DATE_UNSUBSCIBE => 'Дата отписки',
		]
];
 