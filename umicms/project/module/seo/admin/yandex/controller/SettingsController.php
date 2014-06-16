<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\yandex\controller;

use umicms\project\admin\api\component\DefaultQueryAdminComponent;
use umicms\project\admin\api\controller\CollectionComponentLayoutController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends CollectionComponentLayoutController
{
    private $controls = [
        'yandexWebmasterReport' => [],
    ];

    private $layout = [
        'contents' => [
            'emptyContext' => [
                'yandexWebmasterReport' => []
            ]
        ],
    ];

    /**
     * {@inheritdoc}
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
     * @return DefaultQueryAdminComponent
     */
    protected function getComponent()
    {
        return $this->getContext()->getComponent();
    }
}
