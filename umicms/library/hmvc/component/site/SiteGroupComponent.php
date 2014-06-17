<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\site;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;

/**
 * Компонент сайта, имеющий дочерние компоненты.
 */
class SiteGroupComponent extends SiteComponent
{
    /**
     * {@inheritdoc}
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            'index' => 'umicms\hmvc\component\site\SiteStructurePageController'
        ],

        self::OPTION_ACL => [
            IAclFactory::OPTION_ROLES => [
                'viewer' => []
            ],
            IAclFactory::OPTION_RULES => [
                'viewer' => [
                    'controller:index' => []
                ]
            ]
        ],

        SiteComponent::OPTION_ROUTES      => [
            'component' => [
                'type' => 'SiteComponentRoute'
            ],
            'index' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => 'index'
                ]
            ]
        ]
    ];
}
 