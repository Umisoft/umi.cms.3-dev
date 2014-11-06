<?php

namespace umicms\project\module\surveys\site;

use umi\acl\IAclFactory;
use umicms\hmvc\component\site\SitePageComponent;

return [

    SitePageComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SitePageComponent',
    SitePageComponent::OPTION_COLLECTION_NAME => 'survey',

    SitePageComponent::OPTION_CONTROLLERS => [
        'page' => __NAMESPACE__ . '\controller\PageController'
    ],

    SitePageComponent::OPTION_WIDGET => [
        'voteForm' => __NAMESPACE__ . '\widget\VoteFormWidget',
        'voteResults' => __NAMESPACE__ . '\widget\VoteResultsWidget',
    ],

    SitePageComponent::OPTION_ACL => [
        IAclFactory::OPTION_ROLES => [
            'resultsViewer' => [],
        ],
        IAclFactory::OPTION_RULES => [
            'viewer' => [
                'widget:voteForm' => [],
            ],
            'resultsViewer' => [
                'widget:voteResults' => [],
            ]
        ]
    ],

    SitePageComponent::OPTION_VIEW        => [
        'directories' => ['module/surveys']
    ],
];