<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site;

use umi\route\IRouteFactory;
use umicms\project\site\controller\SiteRestWidgetController;

return [
    SiteApplication::OPTION_CLASS => 'umicms\project\site\SiteApplication',

    SiteApplication::OPTION_SETTINGS => [
        SiteApplication::SETTING_URL_POSTFIX => '',

        SiteApplication::SETTING_DEFAULT_DESCRIPTION => 'UMI.CMS',
        SiteApplication::SETTING_DEFAULT_TITLE => 'UMI.CMS',
        SiteApplication::SETTING_TITLE_PREFIX => 'UMI.CMS - ',
        SiteApplication::SETTING_DEFAULT_KEYWORDS => 'UMI.CMS',
    ],

    SiteApplication::OPTION_COMPONENTS => [
        'structure' => '{#lazy:~/project/module/structure/site/module.config.php}',
        'news' => '{#lazy:~/project/module/news/site/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/site/module.config.php}',
        'search' => '{#lazy:~/project/module/search/site/module.config.php}'
    ],

    SiteApplication::OPTION_CONTROLLERS => [
        SiteApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        SiteApplication::LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
        SiteRestWidgetController::NAME => __NAMESPACE__ . '\controller\SiteRestWidgetController',
    ],

    SiteApplication::OPTION_WIDGET => [
        SiteApplication::ERROR_WIDGET => __NAMESPACE__ . '\widget\ErrorWidget',
    ],

    SiteApplication::OPTION_VIEW        => [
        'type'      => 'twig',
        'extension' => 'twig',
        'directory' => __DIR__ . '/template/twig'
    ],

    SiteApplication::OPTION_ROUTES => [
        'widget' => [
            'type'     => IRouteFactory::ROUTE_SIMPLE,
            'route'    => '/widget/{path:string}',
            'defaults' => [
                'controller' => SiteRestWidgetController::NAME
            ]
        ],
        'page' => [
            'type' => 'SiteStaticPageRoute'
        ],
        'component' => [
            'type' => 'SiteComponentRoute'
        ]
    ]
];
