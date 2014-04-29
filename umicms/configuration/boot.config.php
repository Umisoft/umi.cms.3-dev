<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\configuration;

use umi\route\toolbox\RouteTools;
use umi\session\toolbox\SessionTools;
use umicms\project\Bootstrap;

return [
    /**
     * Конфигурация для регистрации наборов инструментов.
     */
    Bootstrap::OPTION_TOOLS  => [
        require(FRAMEWORK_LIBRARY_DIR . '/config/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/http/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/route/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/session/toolbox/config.php'),
        require(FRAMEWORK_LIBRARY_DIR . '/templating/toolbox/config.php'),

        require(CMS_LIBRARY_DIR . '/hmvc/toolbox/config.php'),
    ],
    /**
     * Настройки инструментов.
     */
    Bootstrap::OPTION_TOOLS_SETTINGS => [

        RouteTools::NAME => [
            'factories' => [
                'route' => [
                    'types' => [
                        'SiteStaticPageRoute' => 'umicms\project\site\route\SiteStaticPageRoute',
                        'SiteComponentRoute' => 'umicms\project\site\route\SiteComponentRoute',
                    ]
                ]
            ]
        ],

        SessionTools::NAME => [
            'storage' => [
                'name' => Bootstrap::SESSION_COOKIE_NAME
            ]
        ]
    ]
];
