<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\component;

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
            self::LIST_CONTROLLER =>  'umicms\project\admin\api\controller\DefaultRestListController',
            self::ITEM_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestItemController',
            self::ACTION_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestActionController',
            self::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestSettingsController',
        ],

        self::OPTION_ACL => [

            IAclFactory::OPTION_ROLES => [
                'editor' => []
            ],
            IAclFactory::OPTION_RESOURCES => [
                'controller:settings',
                'controller:action',
                'controller:item',
                'controller:list'
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

            'settings' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => self::SETTINGS_CONTROLLER
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
 