<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\site\survey;

use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'survey',

    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController'
    ],

    SitePageComponent::OPTION_WIDGET => [
        'voteForm' => __NAMESPACE__ . '\widget\VoteFormWidget',
    ],

    SitePageComponent::OPTION_VIEW        => [
        'directories' => ['module/surveys/survey']
    ],
];