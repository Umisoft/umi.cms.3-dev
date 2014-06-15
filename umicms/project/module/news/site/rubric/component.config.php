<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\rubric;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\component\CmsHierarchicPageComponent;

return [

    CmsHierarchicPageComponent::OPTION_CLASS => 'umicms\project\site\component\CmsHierarchicPageComponent',
    CmsHierarchicPageComponent::OPTION_COLLECTION_NAME => 'newsRubric',

    CmsHierarchicPageComponent::OPTION_CONTROLLERS => [
        'rss' => __NAMESPACE__ . '\controller\NewsRubricRssController'
    ],

    CmsHierarchicPageComponent::OPTION_WIDGET => [
        'view' => __NAMESPACE__ .  '\widget\RubricWidget',
        'newsList' => __NAMESPACE__ . '\widget\RubricNewsListWidget',
        'list' => __NAMESPACE__ .  '\widget\RubricListWidget',
        'tree' => __NAMESPACE__ .  '\widget\RubricTreeWidget',
        'rssLink' => __NAMESPACE__ .  '\widget\RubricNewsRssLinkWidget'
    ],

    CmsHierarchicPageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'rssViewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:view' => [],
                'widget:list' => [],
                'widget:tree' => [],
                'widget:newsList' => []
            ],
            'rssViewer' => [
                'controller:rss' => [],
                'widget:rssLink' => []
            ]
        ]
    ],

    CmsHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/news/rubric']
    ],

    CmsHierarchicPageComponent::OPTION_ROUTES      => [
        'rss' => [
            'type'     => IRouteFactory::ROUTE_REGEXP,
            'route' => '/rss/(?P<url>.+)',
            'defaults' => [
                'controller' => 'rss'
            ]
        ]
    ]
];