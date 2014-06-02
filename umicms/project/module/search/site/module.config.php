<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\site;

use umi\route\IRouteFactory;
use umicms\project\site\component\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\project\site\component\SiteComponent',

    SiteComponent::OPTION_CONTROLLERS => [
        'search' => 'umicms\project\module\search\site\controller\SearchController'
    ],
    SiteComponent::OPTION_WIDGET => [
        'search' => __NAMESPACE__ . '\widget\SearchWidget',
        'fragments' => __NAMESPACE__ . '\widget\SearchFragmentsWidget',
        'highlight' => __NAMESPACE__ . '\widget\HighlightWidget',
    ],

    SiteComponent::OPTION_VIEW        => [
        'directories' => ['module/search'],
    ],

    SiteComponent::OPTION_ROUTES      => [
        'search' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'defaults' => [
                'controller' => 'search'
            ]
        ]
    ]
];
