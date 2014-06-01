<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\files\admin\manager\controller;

use umicms\hmvc\controller\BaseAccessRestrictedController;
use umicms\project\admin\api\controller\DefaultRestSettingsController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends BaseAccessRestrictedController
{
    private $controls = [
        'fileManager' => [
            'action' => '/connector'
        ]
    ];

    private $layout = [
        'emptyContext' => [
            'contents' => [
                'controls' => ['fileManager']
            ]
        ]
    ];

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

    /**
     * {@inheritdoc}
     */
    protected function getSettings()
    {
        return [
            DefaultRestSettingsController::OPTION_INTERFACE_CONTROLS => $this->buildControlsInfo(),
            DefaultRestSettingsController::OPTION_INTERFACE_LAYOUT => $this->layout
        ];
    }

    /**
     * Возвращает информацию о контролах компонента.
     * @return array
     */
    protected function buildControlsInfo()
    {
        $controlsInfo = [];

        foreach($this->controls as $controlName => $options) {
            $options['displayName'] = $this->translate('control:' . $controlName . ':displayName');
            $controlsInfo[$controlName] = $options;
        }

        return $controlsInfo;
    }
}
