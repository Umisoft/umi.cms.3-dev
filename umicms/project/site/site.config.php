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
use umicms\serialization\ISerializerFactory;

return [
    SiteApplication::OPTION_CLASS => 'umicms\project\site\SiteApplication',

    SiteApplication::OPTION_SERIALIZERS => [
        ISerializerFactory::TYPE_XML => [
            'umicms\orm\object\CmsObject' => 'umicms\serialization\xml\object\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\xml\object\CmsElementSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\xml\object\FieldSerializer',
            'umicms\hmvc\view\CmsLayoutView' => 'umicms\serialization\xml\view\CmsLayoutViewSerializer',
            'umicms\hmvc\view\CmsView' => 'umicms\serialization\xml\view\CmsViewSerializer',
        ],
        ISerializerFactory::TYPE_JSON => [
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer',
            'umi\form\fieldset\FieldSet' => 'umicms\serialization\json\form\FieldSetSerializer',
            'umi\form\element\BaseFormElement' => 'umicms\serialization\json\form\BaseFormElementSerializer',
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
    ],

    SiteApplication::OPTION_WIDGET => [
        SiteApplication::ERROR_WIDGET => __NAMESPACE__ . '\widget\ErrorWidget'
    ],

    SiteApplication::OPTION_VIEW        => [
        'directories' => ['.']
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
