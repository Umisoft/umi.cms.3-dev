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
use umicms\project\admin\component\AdminComponent;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        /**
         * @var AdminComponent $component
         */
        $component = $this->getComponent();

        return $this->createViewResponse(
            'settings',
            [
                'settings' => [
                    AdminComponent::OPTION_CONTROLS => $component->getControlsInfo(),
                    AdminComponent::OPTION_INTERFACE => $component->getInterfaceInfo(),
                    'actions' => $component->getActionsInfo()
                ]
            ]
        );
    }
}
 