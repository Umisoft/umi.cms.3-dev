<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace project\umi_docs\module\structure\model\object;

use umicms\project\module\structure\model\object\StaticPage;

/**
 * Страница контроллера.
 *
 * @property string $description описание
 * @property string $returnValue описание
 * @property string $templateName описание
 */
class ControllerPage extends StaticPage
{
    /**
     * Тип объекта
     */
    const TYPE = 'static.controller';
    /**
     * Имя поля для хранения описания контроллера
     */
    const FIELD_DESCRIPTION = 'description';
    /**
     * Имя поля для хранения возвращаемого значения
     */
    const FIELD_RETURN_VALUE = 'returnValue';
    /**
     * Имя поля для хранения используемого шаблона
     */
    const FIELD_TEMPLATE_NAME = 'templateName';
}
 