<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\site\controller\SiteRestWidgetController;
use umicms\serialization\ISerializerFactory;

return [
    SiteApplication::OPTION_CLASS => 'umicms\project\site\SiteApplication',

    SiteApplication::OPTION_SERIALIZERS => [
        ISerializerFactory::TYPE_XML => [
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\xml\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\xml\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\xml\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\xml\orm\CmsObjectSerializer',
            'umicms\project\module\search\model\object\SearchIndex' => 'umicms\serialization\xml\orm\SearchIndexSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\xml\orm\SelectorSerializer',
            'umicms\hmvc\view\CmsTreeView' => 'umicms\serialization\xml\view\CmsTreeViewSerializer',
            'umicms\hmvc\view\CmsTreeNode' => 'umicms\serialization\xml\view\CmsTreeNodeSerializer',
            'umicms\hmvc\view\LocalesView' => 'umicms\serialization\xml\view\LocalesViewSerializer',
            'umi\form\EntityAttributesView' => 'umicms\serialization\xml\view\EntityAttributesViewSerializer',
            'umicms\project\module\structure\model\object\MenuInternalItem' => 'umicms\serialization\xml\orm\MenuItemSerializer',
            'umicms\project\module\structure\model\object\MenuExternalItem' => 'umicms\serialization\xml\orm\MenuItemSerializer',
            'umicms\pagination\CmsPaginator' => 'umicms\serialization\xml\PaginatorSerializer',
        ],
        ISerializerFactory::TYPE_JSON => [
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umicms\project\module\search\model\object\SearchIndex' => 'umicms\serialization\json\orm\SearchIndexSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer',
            'umicms\hmvc\view\CmsTreeView' => 'umicms\serialization\json\view\CmsTreeViewSerializer',
            'umicms\hmvc\view\CmsTreeNode' => 'umicms\serialization\json\view\CmsTreeNodeSerializer',
            'umi\form\EntityAttributesView' => 'umicms\serialization\json\view\EntityAttributesViewSerializer',
            'umicms\project\module\structure\model\object\MenuInternalItem' => 'umicms\serialization\json\orm\MenuItemSerializer',
            'umicms\project\module\structure\model\object\MenuExternalItem' => 'umicms\serialization\json\orm\MenuItemSerializer',
            'umicms\pagination\CmsPaginator' => 'umicms\serialization\json\PaginatorSerializer',
        ]
    ],

    SiteApplication::OPTION_COMPONENTS => [
        'structure' => '{#lazy:~/project/module/structure/site/module.config.php}',
        'news' => '{#lazy:~/project/module/news/site/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/site/module.config.php}',
        'search' => '{#lazy:~/project/module/search/site/module.config.php}',
        'users' => '{#lazy:~/project/module/users/site/module.config.php}'
    ],

    SiteApplication::OPTION_CONTROLLERS => [
        SiteApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        SiteApplication::LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
        SiteRestWidgetController::NAME => __NAMESPACE__ . '\controller\SiteRestWidgetController',
        'captcha' => __NAMESPACE__ . '\controller\CaptchaController',
        'robots' => __NAMESPACE__ . '\controller\RobotsController',
    ],

    SiteApplication::OPTION_WIDGET => [
        SiteApplication::ERROR_WIDGET => __NAMESPACE__ . '\widget\ErrorWidget',
        'topBar' =>  __NAMESPACE__ . '\widget\TopBarWidget'
    ],

    SiteApplication::OPTION_VIEW        => [
        'directories' => ['.']
    ],

    SiteApplication::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'viewer' => [],
            'widgetExecutor' => [],
            'topBarViewer' => []
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'controller:captcha' => [],
                'controller:robots' => []
            ],
            'widgetExecutor' => [
                'controller:widget' => []
            ],
            'topBarViewer' => [
                'widget:topBar' => []
            ]
        ]
    ],

    SiteApplication::OPTION_ROUTES => [
        'page' => [
            'type' => 'SiteStaticPageRoute'
        ],
        'captcha' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/captcha/{key:string}',
            'defaults' => [
                'controller' => 'captcha'
            ]
        ],
        'widget' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/widget/{path:string}',
            'defaults' => [
                'controller' => SiteRestWidgetController::NAME
            ]
        ],
        'robots' => [
            'type' => IRouteFactory::ROUTE_FIXED,
            'route' => '/robots.txt',
            'defaults' => [
                'controller' => 'robots'
            ]
        ],
        'component' => [
            'type' => 'SiteComponentRoute'
        ]
    ]
];
