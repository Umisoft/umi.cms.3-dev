<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\site\rubric;

use umicms\hmvc\component\site\SiteHierarchicPageComponent;

return [
    SiteHierarchicPageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteHierarchicPageComponent',

    SiteHierarchicPageComponent::OPTION_COLLECTION_NAME => 'forumConference',

    SiteHierarchicPageComponent::OPTION_CONTROLLERS => [
    ],

    SiteHierarchicPageComponent::OPTION_WIDGET => [
    ],

    SiteHierarchicPageComponent::OPTION_ACL => [
    ],

    SiteHierarchicPageComponent::OPTION_VIEW => [
        'directories' => ['module/forum/conference']
    ],

    SiteHierarchicPageComponent::OPTION_ROUTES => [
    ]
];