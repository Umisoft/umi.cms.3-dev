<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\project\admin\settings\component\DefaultSettingsComponent;

return [

    DefaultSettingsComponent::OPTION_CLASS => 'umicms\project\admin\settings\component\SettingsComponent',

    DefaultSettingsComponent::OPTION_COMPONENTS => [
        'megaindex' => '{#lazy:~/project/module/seo/settings/megaindex/component.config.php}',
        'yandex' => '{#lazy:~/project/module/seo/settings/yandex/component.config.php}'
    ],
    DefaultSettingsComponent::OPTION_ACL => [

        IAclFactory::OPTION_ROLES => [
            'configurator' => []
        ],
        IAclFactory::OPTION_RESOURCES => [
            'component:megaindex',
            'component:yandex'
        ],
        IAclFactory::OPTION_RULES => [
            'configurator' => [
                'component:megaindex' => [],
                'component:yandex' => []
            ]
        ]
    ],
    DefaultSettingsComponent::OPTION_ROUTES => [
        'component' => [
            'type' => IRouteFactory::ROUTE_SIMPLE,
            'route' => '/{component}'
        ]
    ]
];