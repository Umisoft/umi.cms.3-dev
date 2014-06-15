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
use umicms\project\site\component\CmsPageComponent;

return [

    CmsPageComponent::OPTION_CLASS => 'umicms\project\site\component\CmsPageComponent',
    CmsPageComponent::OPTION_COLLECTION_NAME => 'newsSubject',
    
    CmsPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\NewsSubjectRssController'
    ],

    CmsPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ . '\widget\SubjectWidget',
        'newsList' => __NAMESPACE__ . '\widget\SubjectNewsListWidget',
        'list' => __NAMESPACE__ . '\widget\SubjectListWidget',
        'rssLink' => __NAMESPACE__ . '\widget\SubjectNewsRssLinkWidget'
    ],

    CmsPageComponent::OPTION_ACL => [
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

    CmsPageComponent::OPTION_VIEW        => [
        'directories' => ['module/news/subject']
    ],

    CmsPageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/rss/{slug}',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];