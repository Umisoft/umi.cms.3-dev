<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 