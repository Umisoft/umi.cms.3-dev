<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use project\module\structure\model\object\ControllerPage;
use project\module\structure\model\object\WidgetPage;

return [
    'en-US' => [

    ],

    'ru-RU' => [
        WidgetPage::FIELD_PARAMETERS => 'Параметры виджета (автогенерируемое)',
        WidgetPage::FIELD_DESCRIPTION => 'Длинное описание класса (автогенерируемое )',
        WidgetPage::FIELD_RETURN_VALUE => 'Параметры, идущие на шаблонизацию (автогенерируемое)',
        WidgetPage::FIELD_TWIG_EXAMPLE => 'Пример шаблона для Twig (автогенерируемое)',
        WidgetPage::FIELD_PHP_EXAMPLE => 'Пример шаблона для PHP (автогенерируемое)',
        ControllerPage::FIELD_TEMPLATE_NAME => 'Имя шаблона вывода (автогенерируемое)'
    ]
];
 