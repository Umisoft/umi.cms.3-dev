<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use umicms\project\module\dispatch\model\object\BaseSubscriber;
 use umicms\project\module\dispatch\model\object\Subscriber;
 use umicms\project\module\dispatch\model\object\SubscriberUser;

return [
		'en-US' => [
			BaseSubscriber::FIELD_EMAIL => 'E-mail',
			BaseSubscriber::FIELD_FIRST_NAME => 'First name',
			BaseSubscriber::FIELD_LAST_NAME => 'Last name',
			BaseSubscriber::FIELD_MIDDLE_NAME => 'Middle name',
			BaseSubscriber::FIELD_SEX => 'Sex',
			BaseSubscriber::FIELD_DISPATCH => 'Dispatches',
			BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => 'Unsubscribe mailing',
			BaseSubscriber::FIELD_PROFILE => 'Profile user',
			'additional' => 'Additional',
		],

		'ru-RU' => [
			BaseSubscriber::FIELD_EMAIL => 'E-mail',
			BaseSubscriber::FIELD_FIRST_NAME => 'Имя',
			BaseSubscriber::FIELD_LAST_NAME => 'Фамилия',
			BaseSubscriber::FIELD_MIDDLE_NAME => 'Отчество',
			BaseSubscriber::FIELD_SEX => 'Пол',
			BaseSubscriber::FIELD_DISPATCH => 'Рассылки',
			BaseSubscriber::FIELD_UNSUBSCRIBE_DISPATCHES => 'Отписанные рассылки',
			BaseSubscriber::FIELD_PROFILE => 'Профиль пользователя',
			'additional' => 'Дополнительно',
		]
];
 