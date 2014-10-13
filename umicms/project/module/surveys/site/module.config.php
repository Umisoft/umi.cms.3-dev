<?php

namespace umicms\project\module\surveys\site;

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
        'directories' => ['module/surveys']
    ],
];