<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\component;

use umicms\hmvc\component\BaseComponent;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\admin\api\controller\BaseRestActionController;

/**
 * Компонент административной панели.
 */
class AdminComponent extends BaseComponent implements IUrlManagerAware
{

    use TUrlManagerAware;

    /**
     * Имя опции для задания контролов компонента.
     */
    const OPTION_CONTROLS = 'controls';
    /**
     * Имя опции для задания настроек интерфейсного отображения контролов
     */
    const OPTION_INTERFACE = 'layout';

    /**
     * Контроллер для выполнения действий
     */
    const ACTION_CONTROLLER = 'action';
    /**
     * Контроллер для отображеня настроек компонента
     */
    const SETTINGS_CONTROLLER = 'settings';
    /**
     * Контроллер для выполнения RUD-операций над объектом
     */
    const ITEM_CONTROLLER = 'item';
    /**
     * Контроллер для выполнения CRUD-операций над списком объектов
     */
    const LIST_CONTROLLER = 'list';

    /**
     * Возвращает информацию о контролах компонента.
     * @return array
     */
    public function getControlsInfo()
    {
        $controls = isset($this->options[self::OPTION_CONTROLS]) ? $this->options[self::OPTION_CONTROLS] : [];
        $controls = $this->configToArray($controls, true);

        $controlsInfo = [];

        foreach($controls as $controlName => $options) {
            $options['name'] = $controlName;
            $options['displayName'] = $this->translate($controlName . ':control:displayName');

            $controlsInfo[] = $options;
        }

        return $controlsInfo;
    }

    /**
     * Возвращает информацию об интерфейсном отображении контролов.
     * @return array
     */
    public function getInterfaceInfo()
    {
        return isset($this->options[self::OPTION_INTERFACE]) ? $this->options[self::OPTION_INTERFACE] : [];
    }

    /**
     * Возвращает информацию о доступных действиях компонентов.
     * @return array
     */
    public function getActionsInfo()
    {
        $actions = [];

        if ($this->hasController(self::ACTION_CONTROLLER)) {
            $controller = $this->getController(self::ACTION_CONTROLLER);
            if ($controller instanceof BaseRestActionController) {
                if ($queryActions = $controller->getQueryActions()) {
                    $actions['query'] = $queryActions;
                }
                if ($modifyActions = $controller->getModifyActions()) {
                    $actions['modify'] = $modifyActions;
                }
            }
        }

        return $actions;
    }

    /**
     * Возвращает информацию о компоненте и дочерних компонентах на всю глубину.
     * @return array
     */
    public function getComponentInfo()
    {
        $componentInfo = [
            'name'        => $this->getName(),
            'displayName' => $this->translate($this->getName() . ':component:displayName'),
            'resource' => $this->getUrlManager()->getComponentResourceUrl($this)
        ];

        $components = [];

        foreach ($this->getChildComponentNames() as $componentName) {
            /**
             * @var AdminComponent $component
             */
            $component = $this->getChildComponent($componentName);
            $components[] = $component->getComponentInfo();
        }

        if ($components) {
            $componentInfo['components'] = $components;
        }

        return $componentInfo;
    }

}
 