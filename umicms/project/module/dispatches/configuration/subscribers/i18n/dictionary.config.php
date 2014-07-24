<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

 use umicms\project\module\dispatches\model\object\Subscribers;

return [
		'en-US' => [
			Subscribers::FIELD_EMAIL => 'E-mail',
			Subscribers::FIELD_FIRST_NAME => 'First name',
			Subscribers::FIELD_LAST_NAME => 'Last name',
			Subscribers::FIELD_MIDDLE_NAME => 'Middle name',
			Subscribers::FIELD_SEX => 'Sex',
			Subscribers::FIELD_DISPATCHES => 'Dispatches',
			Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES => 'Unsubscribe mailing'
		],

		'ru-RU' => [
			Subscribers::FIELD_EMAIL => 'E-mail',
			Subscribers::FIELD_FIRST_NAME => 'Имя',
			Subscribers::FIELD_LAST_NAME => 'Фамилия',
			Subscribers::FIELD_MIDDLE_NAME => 'Отчество',
			Subscribers::FIELD_SEX => 'Пол',
			Subscribers::FIELD_DISPATCHES => 'Рассылки',
			Subscribers::FIELD_UNSUBSCRIBE_DISPATCHES => 'Отписанные рассылки'
		]
];
 