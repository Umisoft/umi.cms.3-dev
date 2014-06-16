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
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Компонент для группировки компонентов настроек
 */
class SettingsGroupComponent extends AdminComponent
{
    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::INTERFACE_LAYOUT_CONTROLLER => 'umicms\hmvc\component\admin\settings\GroupLayoutController',
        ],

        self::OPTION_ROUTES      => [
            'layout' => [
                'type'     => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => self::INTERFACE_LAYOUT_CONTROLLER
                ],
                'subroutes' => [
                    'component' => [
                        'type'     => IRouteFactory::ROUTE_SIMPLE,
                        'route' => '/{component}'
                    ]
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
     * {@inheritdoc}
     */
    protected function getChildComponentsAclConfig()
    {
        $config = parent::getChildComponentsAclConfig();
        foreach ($config[IAclFactory::OPTION_RULES] as $resources) {
            $resources[self::INTERFACE_LAYOUT_CONTROLLER . BaseCmsController::ACL_RESOURCE_PREFIX] = [];
        }
        return $config;
    }
}
 