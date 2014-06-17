<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'authorization' => '{#lazy:~/project/module/users/site/authorization/component.config.php}',
        'registration' => '{#lazy:~/project/module/users/site/registration/component.config.php}',
        'restoration' => '{#lazy:~/project/module/users/site/restoration/component.config.php}',
        'profile' => '{#lazy:~/project/module/users/site/profile/component.config.php}',
    ],

    SiteGroupComponent::OPTION_VIEW        => [
        'directories' => ['module/users']
    ]
];