<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\component;

use umicms\hmvc\component\BaseCmsComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\admin\layout\action\Action;

/**
 * Простой компонент административной панели.
 */
class AdminComponent extends BaseCmsComponent implements IUrlManagerAware
{
    use TUrlManagerAware;

    /**
     * Опция для задания дополнительного списка доступных действий на запрос данных
     */
    const OPTION_QUERY_ACTIONS = 'queryActions';
    /**
     * Опция для задания дополнительного списка доступных действий на изменение данных
     */
    const OPTION_MODIFY_ACTIONS = 'modifyActions';
    /**
     * Контроллер для выполнения действий
     */
    const ACTION_CONTROLLER = 'action';
    /**
     * Контроллер для получения настроек интерфейса компонента
     */
    const INTERFACE_LAYOUT_CONTROLLER = 'interfaceLayout';
    /**
     * Контроллер для выполнения RUD-операций над объектом
     */
    const ITEM_CONTROLLER = 'item';
    /**
     * Контроллер для выполнения CRUD-операций над списком объектов
     */
    const LIST_CONTROLLER = 'list';

    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        return [
            'name'        => $this->getName(),
            'displayName' => $this->translate('component:' . $this->getName() . ':displayName'),
            'resource' => $this->getUrlManager()->getAdminComponentResourceUrl($this)
        ];
    }

    /**
     * Возвращает список доступных действий на запрос данных.
     * @return Action[] массив вида [actionName => Action, ...]
     */
    public function getQueryActions()
    {
        $actions = [];
        if (isset($this->options[self::OPTION_QUERY_ACTIONS])) {
            foreach ($this->options[self::OPTION_QUERY_ACTIONS] as $actionName) {
                $actions[$actionName] = $this->createQueryAction($actionName);
            }
        }

        return $actions;
    }

    /**
     * Возвращает список доступных действий на изменение данных.
     * @return Action[] массив вида [actionName => Action, ...]
     */
    public function getModifyActions()
    {
        $actions = [];

        if (isset($this->options[self::OPTION_MODIFY_ACTIONS])) {
            foreach ($this->options[self::OPTION_MODIFY_ACTIONS] as $actionName) {
                $actions[$actionName] = $this->createModifyAction($actionName);
            }
        }

        return $actions;
    }

    /**
     * Создает новое REST-действие компонента для выборки данных
     * @param string $actionName имя действия
     * @return Action
     */
    protected function createQueryAction($actionName)
    {
        return new Action(
            $this->getUrlManager()->getAdminComponentActionResourceUrl($this, $actionName),
            Action::TYPE_QUERY
        );
    }

    /**
     * Создает новое REST-действие компонента для модификации данных
     * @param string $actionName имя действия
     * @return Action
     */
    protected function createModifyAction($actionName)
    {
        return new Action(
            $this->getUrlManager()->getAdminComponentActionResourceUrl($this, $actionName),
            Action::TYPE_MODIFY
        );
    }
}
 