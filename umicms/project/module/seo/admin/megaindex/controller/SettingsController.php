<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\megaindex\controller;

use umicms\project\admin\rest\component\DefaultQueryAdminComponent;
use umicms\project\admin\rest\controller\LayoutController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends LayoutController
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
