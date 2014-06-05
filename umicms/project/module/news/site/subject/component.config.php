<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\subject;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\DefaultSitePageComponent;

return [

    DefaultSitePageComponent::OPTION_CLASS => 'umicms\project\site\component\DefaultSitePageComponent',
    DefaultSitePageComponent::OPTION_COLLECTION_NAME => 'newsSubject',
    
    DefaultSitePageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\NewsSubjectRssController'
    ],

    DefaultSitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\SubjectWidget',
        'newsList' => __NAMESPACE__ . '\widget\SubjectNewsListWidget',
        'list' => __NAMESPACE__ . '\widget\SubjectListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\SubjectNewsRssLinkWidget'
    ],

    DefaultSitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'controller:rss',
            'widget:view',
            'widget:list',
            'widget:newsList',
            'widget:rssLink'
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:newsList' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],

    DefaultSitePageComponent::OPTION_VIEW        => [
        'directories' => ['module/news/subject']
    ],

    DefaultSitePageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];