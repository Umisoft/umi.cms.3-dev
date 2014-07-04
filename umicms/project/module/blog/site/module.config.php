<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\site;

use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'post' => '{#lazy:~/project/module/blog/site/post/component.config.php}',
        'draft' => '{#lazy:~/project/module/blog/site/draft/component.config.php}',
        'moderate' => '{#lazy:~/project/module/blog/site/moderate/component.config.php}',
        'reject' => '{#lazy:~/project/module/blog/site/reject/component.config.php}',
        'category' => '{#lazy:~/project/module/blog/site/category/component.config.php}',
        'author' => '{#lazy:~/project/module/blog/site/author/component.config.php}',
        'tag' => '{#lazy:~/project/module/blog/site/tag/component.config.php}',
        'comment' => '{#lazy:~/project/module/blog/site/comment/component.config.php}'
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/blog']
    ]
];