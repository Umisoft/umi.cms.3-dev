<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\object;

use umicms\base\object\CmsElement;

/**
 * Базовый элемент структуры.
 *
 * @property string $componentPath путь до компонента-обработчика
 * @property Layout|null $layout шаблон для вывода
 */
abstract class StructureElement extends CmsElement
{
    /**
     *  Имя поля для хранения пути компонента-обработчика
     */
    const FIELD_COMPONENT_PATH = 'componentPath';
    /**
     *  Имя поля для хранения шаблона
     */
    const FIELD_LAYOUT = 'layout';
}
