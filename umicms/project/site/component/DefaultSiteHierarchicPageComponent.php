<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\component;

use umi\acl\IAclFactory;
use umi\orm\collection\TCollectionManagerAware;
use umi\route\IRouteFactory;

/**
 * Компонент для вывода иерархических страниц на сайте.
 */
class DefaultSiteHierarchicPageComponent extends BaseDefaultSitePageComponent
{
    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            'index' => 'umicms\project\site\controller\DefaultStructurePageController',
            'page' => 'umicms\project\site\controller\DefaultPageController'
        ],

        self::OPTION_ACL => [
            IAclFactory::OPTION_ROLES => [
                'viewer' => [],
            ],
            IAclFactory::OPTION_RESOURCES => [
                'index' => 'controller:index',
                'page' => 'controller:page'
            ],
            IAclFactory::OPTION_RULES => [
                'viewer' => [
                    'controller:index' => [],
                    'controller:page' => []
                ]
            ]
        ],

        self::OPTION_ROUTES => [
            'page' => [
                'type'     => IRouteFactory::ROUTE_REGEXP,
                'priority'  => 100,
                'route'    => '/(?P<uri>.+)',
                'defaults' => [
                    'controller' => 'page'
                ]
            ],
            'index' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'priority'  => 200,
                'defaults' => [
                    'controller' => 'index'
                ]
            ]
        ]
    ];
}
 