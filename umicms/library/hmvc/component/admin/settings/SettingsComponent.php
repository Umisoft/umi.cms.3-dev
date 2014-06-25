<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\settings;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\exception\RuntimeException;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Компонент настроек
 */
class SettingsComponent extends AdminComponent
{
    /**
     * Опция для задания алиаса пути к настройкам, управляемым компонентом
     */
    const OPTION_SETTINGS_CONFIG_ALIAS = 'settingsConfigAlias';

    /**
     * {@inheritdoc}
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::INTERFACE_LAYOUT_CONTROLLER => 'umicms\hmvc\component\admin\settings\LayoutController',
            self::ACTION_CONTROLLER => 'umicms\hmvc\component\admin\settings\ActionController'
        ],

        self::OPTION_MODIFY_ACTIONS => [
            'save'
        ],

        self::OPTION_ACL => [
            IAclFactory::OPTION_ROLES => [
                'configurator' => []
            ],
            IAclFactory::OPTION_RULES => [
                'configurator' => [
                    'interfaceLayout:controller' => [],
                    'action:controller' => []
                ]
            ]
        ],

        self::OPTION_ROUTES => [
            'action'     => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'priority'  => 200,
                'route'    => '/action/{action}',
                'defaults' => [
                    'controller' => self::ACTION_CONTROLLER
                ]
            ],
            'layout' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'priority'  => 300,
                'defaults' => [
                    'controller' => self::INTERFACE_LAYOUT_CONTROLLER
                ]
            ]
        ]
    ];

    /**
     * Возвращает алиас пути к настройкам, управляемым компонентом
     * @throws RuntimeException
     * @return string
     */
    public function getSettingsConfigAlias()
    {
        if (!isset($this->options[self::OPTION_SETTINGS_CONFIG_ALIAS])) {
            throw new RuntimeException(
                $this->translate(
                    'Option "{option}" is required for component "{path}".',
                    [
                        'option' => self::OPTION_SETTINGS_CONFIG_ALIAS,
                        'path' => $this->getPath()
                    ]
                )
            );
        }

        return $this->options[self::OPTION_SETTINGS_CONFIG_ALIAS];
    }
}
 