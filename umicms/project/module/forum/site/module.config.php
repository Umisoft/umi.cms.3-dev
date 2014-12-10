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
        'conference' => '{#lazy:~/project/module/forum/site/conference/component.config.php}',
        'theme' => '{#lazy:~/project/module/forum/site/theme/component.config.php}',
        'author' => '{#lazy:~/project/module/forum/site/author/component.config.php}',
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/forum']
    ]
];