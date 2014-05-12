<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\seo\admin\megaindex\controller;

use umicms\project\admin\api\component\DefaultQueryAdminComponent;
use umicms\project\admin\api\controller\DefaultRestSettingsController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends DefaultRestSettingsController
{
    private $controls = [
        'megaindexReport' => []
    ];

    private $layout = [
        'contents' => [
            'emptyContext' => [
                'megaindexReport' => []
            ]
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function getSettings()
    {
        return [
            'controls' => $this->controls,
            'layout' => $this->layout,
            'actions' => $this->buildActionsInfo()
        ];
    }

    /**
     * Возвращает информацию о доступных действиях компонентов.
     * @return array
     */
    protected function buildActionsInfo()
    {
        $actions = [];
        $component = $this->getComponent();

        foreach ($component->getQueryActions() as $actionName) {
            $actions[$actionName] = [
                'type' => 'query',
                'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
            ];
        }

        return $actions;
    }

    /**
     * @return DefaultQueryAdminComponent
     */
    protected function getComponent()
    {
        return $this->getContext()->getComponent();
    }
}
