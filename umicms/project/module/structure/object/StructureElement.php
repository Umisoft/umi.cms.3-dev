<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\object;

use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Базовый элемент структуры.
 *
 * @property string $componentPath путь до компонента-обработчика
 * @property string $componentName имя компонента-обработчика
 * @property bool $inMenu признак включения в меню
 * @property int $submenuState состояние дочернего меню
 */
abstract class StructureElement extends CmsHierarchicObject implements ICmsPage
{
    use TCmsPage;

    /**
     *  Имя поля для хранения пути компонента-обработчика
     */
    const FIELD_COMPONENT_PATH = 'componentPath';
    /**
     * Имя поля для хранения имени компонента-обработчика
     */
    const FIELD_COMPONENT_NAME = 'componentName';
    /**
     * Имя поля для хранения признака включения в меню
     */
    const FIELD_IN_MENU = 'inMenu';
    /**
     * Имя поля для хранения способа отображения подменю
     */
    const FIELD_SUBMENU_STATE = 'submenuState';
    /**
     * Подменю никогда не развернуто
     */
    const SUBMENU_NEVER_SHOWN = 0;
    /**
     * Подменю развернуто, если в нем находится текущая страница
     */
    const SUBMENU_CURRENT_SHOWN = 1;
    /**
     * Подменю всегда развернуто
     */
    const SUBMENU_ALWAYS_SHOWN = 2;
}
