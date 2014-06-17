<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site;

use umicms\hmvc\component\site\SiteGroupComponent;

return [
    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'rubric' => '{#lazy:~/project/module/news/site/rubric/component.config.php}',
        'item' => '{#lazy:~/project/module/news/site/item/component.config.php}',
        'subject' => '{#lazy:~/project/module/news/site/subject/component.config.php}'
    ],

    SiteGroupComponent::OPTION_VIEW        => [
        'directories' => ['module/news']
    ]
];