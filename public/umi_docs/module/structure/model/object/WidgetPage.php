<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace project\module\structure\model\object;

use umicms\project\module\structure\model\object\StaticPage;

/**
 * Страница виджета.
 *
 * @property string $parameters параметры виджета
 * @property string $description описание
 * @property string $returnValue описание возвращаемого значения
 * @property string $twigExample пример шаблона для twig
 * @property string $phpExample пример шаблона для php
 */
class WidgetPage extends StaticPage
{
    /**
     * Тип объекта
     */
    const TYPE = 'static.widget';
    /**
     *  Имя поля для хранения параметров виджета
     */
    const FIELD_PARAMETERS = 'parameters';
    /**
     * Имя поля для хранения описания виджета
     */
    const FIELD_DESCRIPTION = 'description';
    /**
     * Имя поля для хранения возвращаемого значения
     */
    const FIELD_RETURN_VALUE = 'returnValue';
    /**
     * Имя поля для хранения twig-шаблона
     */
    const FIELD_TWIG_EXAMPLE = 'twigExample';
    /**
     * Имя поля для хранения php-шаблона
     */
    const FIELD_PHP_EXAMPLE = 'phpExample';

}
 