<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umicms\hmvc\controller\BaseController;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\project\admin\component\AdminComponent;

/**
 * Базовый контроллер вывода настроек компонента
 */
abstract class BaseSettingsController extends BaseController implements IUrlManagerAware
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
     * Имя опции для задания действий в компоненте
     */
    const OPTION_INTERFACE_ACTIONS = 'actions';

    /**
     * Возвращает настройки компонента
     * @return array
     */
    abstract protected function getSettings();

    /**
     * Возвращает информацию о контролах компонента.
     * @param array $controls список контролов с опциями
     * @return array
     */
    protected function buildControlsInfo(array $controls)
    {
       $controlsInfo = [];

       foreach($controls as $controlName => $options) {
            $options['displayName'] = $this->translate('control:' . $controlName . ':displayName');
            $controlsInfo[$controlName] = $options;
        }

        return $controlsInfo;
    }

    /**
     * Возвращает информацию об интерфейсном отображении контролов.
     * @param array $interfaceOptions настройки отображения контролов
     * @return array
     */
    protected function buildLayoutInfo(array $interfaceOptions)
    {
        return $interfaceOptions;
    }

    /**
     * Возвращает информацию о доступных действиях компонентов.
     * @return array
     */
    protected function buildActionsInfo()
    {
        $actions = [];
        /**
         * @var AdminComponent $component
         */
        $component = $this->getComponent();

        if ($component->hasController(AdminComponent::ACTION_CONTROLLER)) {
            $controller = $component->getController(AdminComponent::ACTION_CONTROLLER);
            if ($controller instanceof BaseRestActionController) {

                foreach ($controller->getQueryActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'query',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
                    ];
                }

                foreach ($controller->getModifyActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'modify',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
                    ];
                }
            }
        }

        return $actions;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
 