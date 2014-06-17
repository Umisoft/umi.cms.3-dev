<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\site;

use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'menu' => '{#lazy:~/project/module/structure/site/menu/component.config.php}',
        'infoblock' => '{#lazy:~/project/module/structure/site/infoblock/component.config.php}'
    ],

    SiteGroupComponent::OPTION_VIEW        => [
        'directories' => ['module/structure']
    ]
];