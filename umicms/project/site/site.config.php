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

use umi\route\IRouteFactory;
use umicms\project\site\controller\SiteRestWidgetController;
use umicms\serialization\ISerializerFactory;

return [
    SiteApplication::OPTION_CLASS => 'umicms\project\site\SiteApplication',

    SiteApplication::OPTION_SERIALIZERS => [
        ISerializerFactory::TYPE_XML => [
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\xml\orm\ObjectTypeSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\xml\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\xml\orm\CmsObjectSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\xml\orm\SelectorSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\xml\orm\FieldSerializer',
            'umicms\hmvc\view\CmsLayoutView' => 'umicms\serialization\xml\view\CmsLayoutViewSerializer',
            'umicms\hmvc\view\CmsView' => 'umicms\serialization\xml\view\CmsViewSerializer',
        ],
        ISerializerFactory::TYPE_JSON => [
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer'
        ]
    ],

    SiteApplication::OPTION_SETTINGS => '{#lazy:~/project/site/site.settings.config.php}',

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
    ],

    SiteApplication::OPTION_WIDGET => [
        SiteApplication::ERROR_WIDGET => __NAMESPACE__ . '\widget\ErrorWidget'
    ],

    SiteApplication::OPTION_VIEW        => [
        'directories' => ['.']
    ],

    SiteApplication::OPTION_ROUTES => [
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
        'page' => [
            'type' => 'SiteStaticPageRoute'
        ],
        'component' => [
            'type' => 'SiteComponentRoute'
        ]
    ]
];
