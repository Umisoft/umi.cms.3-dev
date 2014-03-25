<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\yandex\controller;

use umicms\project\admin\api\controller\BaseSettingsController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends BaseSettingsController
{
    private $controls = [
        'yandexWebmasterReport' => [],
    ];

    private $layout = [
        'emptyContext' => [
            'contents' => [
                'controls' => ['yandexWebmasterReport']
            ]
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function getSettings()
    {
        return [
            self::OPTION_INTERFACE_CONTROLS => $this->buildControlsInfo($this->controls),
            self::OPTION_INTERFACE_LAYOUT => $this->buildLayoutInfo($this->layout),
            self::OPTION_INTERFACE_ACTIONS => $this->buildActionsInfo()
        ];
    }
}
