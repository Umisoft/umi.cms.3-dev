<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\object;

use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\IProjectSettingsAware;
use umicms\project\TProjectSettingsAware;

/**
 * Базовый элемент структуры.
 *
 * @property string $componentPath путь до компонента-обработчика
 * @property string $componentName имя компонента-обработчика
 * @property bool $inMenu признак включения в меню
 * @property int $submenuState состояние дочернего меню
 */
abstract class StructureElement extends CmsHierarchicObject implements ICmsPage, ILockedAccessibleObject, IProjectSettingsAware
{
    use TProjectSettingsAware;

    use TCmsPage {
        TCmsPage::getPageUrl as protected getPageUrlInternal;
    }

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

    /**
     * Проверяет, явяляется ли страница страницей по умолчанию
     * @return bool
     */
    public function getIsDefault()
    {
        return $this->getSiteDefaultPageGuid() === $this->guid;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageUrl($isAbsolute = false)
    {
        if ($this->getIsDefault()) {
            return $this->geturlManager()->getProjectUrl($isAbsolute);
        }

        return $this->getPageUrlInternal($isAbsolute);
    }
}
