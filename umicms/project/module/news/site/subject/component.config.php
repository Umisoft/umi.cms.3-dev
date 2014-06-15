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
use umicms\project\site\component\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\project\site\component\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'newsSubject',
    
    SitePageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\NewsSubjectRssController'
    ],

    SitePageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\SubjectWidget',
        'newsList' => __NAMESPACE__ . '\widget\SubjectNewsListWidget',
        'list' => __NAMESPACE__ . '\widget\SubjectListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\SubjectNewsRssLinkWidget'
    ],

    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => []
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

    SitePageComponent::OPTION_VIEW        => [
        'directories' => ['module/news/subject']
    ],

    SitePageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];