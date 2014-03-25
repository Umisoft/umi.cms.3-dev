<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\component;

use umi\acl\IAclResource;
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
    const OPTION_INTERFACE_CONTROLS = 'controls';
    /**
     * Имя опции для задания настроек интерфейсного отображения контролов
     */
    const OPTION_INTERFACE_LAYOUT = 'layout';

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
        $controls = isset($this->options[self::OPTION_INTERFACE_CONTROLS]) ? $this->options[self::OPTION_INTERFACE_CONTROLS] : [];
        $controls = $this->configToArray($controls, true);

        $controlsInfo = [];

        foreach($controls as $controlName => $options) {
            $options['displayName'] = $this->translate('control:' . $controlName . ':displayName');
            $controlsInfo[$controlName] = $options;
        }

        return $controlsInfo;
    }

    /**
     * Возвращает информацию об интерфейсном отображении контролов.
     * @return array
     */
    public function getInterfaceInfo()
    {
        return isset($this->options[self::OPTION_INTERFACE_LAYOUT]) ? $this->options[self::OPTION_INTERFACE_LAYOUT] : [];
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

                foreach ($controller->getQueryActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'query',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionUrl($this, $actionName)
                    ];
                }

                foreach ($controller->getModifyActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'modify',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionUrl($this, $actionName)
                    ];
                }
            }
        }

        return $actions;
    }

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
}
 