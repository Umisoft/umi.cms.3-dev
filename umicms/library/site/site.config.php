<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\site;

use umicms\site\SiteApplication;

return [
    SiteApplication::OPTION_CLASS => 'umicms\site\SiteApplication',

    SiteApplication::OPTION_SETTINGS => [
        SiteApplication::SETTING_URL_POSTFIX => ''
    ],

    SiteApplication::OPTION_COMPONENTS => [
        'structure' => '{#lazy:~/project/module/structure/site/module.config.php}',
        'news' => '{#lazy:~/project/module/news/site/module.config.php}',
        'blog' => '{#lazy:~/project/module/blog/site/module.config.php}'
    ],

    SiteApplication::OPTION_CONTROLLERS => [
        SiteApplication::ERROR_CONTROLLER   => __NAMESPACE__ . '\controller\ErrorController',
        SiteApplication::LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
    ],

    SiteApplication::OPTION_VIEW        => [
        'type'      => 'php',
        'extension' => 'phtml',
        'directory' => __DIR__ . '/template/php'
    ],

    SiteApplication::OPTION_ROUTES => [
        'siteRoute' => [
            'type' => 'siteRoute'
        ]
    ]
];