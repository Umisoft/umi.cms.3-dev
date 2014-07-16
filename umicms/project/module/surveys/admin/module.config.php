<?php

namespace umicms\project\module\surveys\admin;

use umi\route\IRouteFactory;
use umicms\hmvc\component\admin\AdminComponent;

return [

    AdminComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\AdminComponent',

    AdminComponent::OPTION_COMPONENTS => [
        'survey' => '{#lazy:~/project/module/surveys/admin/survey/component.config.php}',
        'answer' => '{#lazy:~/project/module/surveys/admin/answer/component.config.php}',
    ],

    AdminComponent::OPTION_ROUTES      => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];