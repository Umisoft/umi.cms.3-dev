<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\rest\component;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\component\AdminComponent;

/**
 * Компонент административной панели компанента работающего без коллекции.
 */
class DefaultQueryAdminComponent extends AdminComponent
{
    /**
     * Опция для задания дополнительного списка доступных действий на запрос данных
     */
    const OPTION_QUERY_ACTIONS = 'queryActions';

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::ACTION_CONTROLLER => 'umicms\project\admin\rest\controller\DefaultRestActionController',
            self::INTERFACE_LAYOUT_CONTROLLER => 'umicms\project\admin\rest\controller\CollectionComponentLayoutController',
        ],

        self::OPTION_ACL => [

            IAclFactory::OPTION_ROLES => [
                'editor' => []
            ],
            IAclFactory::OPTION_RULES => [
                'editor' => [
                    'controller:settings' => [],
                    'controller:action' => [],
                    'controller:item' => [],
                    'controller:list' => []
                ],
            ]
        ],

        self::OPTION_ROUTES      => [
            'action' => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'route'    => '/action/{action}',
                'defaults' => [
                    'controller' => self::ACTION_CONTROLLER
                ]
            ],

            'layout' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => self::INTERFACE_LAYOUT_CONTROLLER
                ]
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);
        parent::__construct($name, $path, $options);
    }

    /**
     * Возвращает список доступных действий на запрос данных.
     * @return array
     */
    public function getQueryActions()
    {
        $actions = [];
        if (isset($this->options[self::OPTION_QUERY_ACTIONS])) {
            $actions = $this->configToArray($this->options[self::OPTION_QUERY_ACTIONS]);
        }

        return $actions;
    }
}
 