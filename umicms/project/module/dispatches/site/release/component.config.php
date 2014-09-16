<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\site\release;

use umi\acl\IAclFactory;
use umi\route\IRouteFactory;
use umicms\hmvc\component\site\SiteComponent;

return [

    SiteComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteComponent',
    SiteComponent::OPTION_COLLECTION_NAME => 'dispatchRelease',

    SiteComponent::OPTION_WIDGET => [
        //'release' => __NAMESPACE__ . '\widget\DispatchReleaseWidget',
    ],

    SiteComponent::OPTION_VIEW => [
        'directories' => ['module/dispatch/release']
    ]
];