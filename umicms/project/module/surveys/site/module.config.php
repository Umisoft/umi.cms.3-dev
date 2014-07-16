<?php

namespace umicms\project\module\surveys\site;

use umicms\hmvc\component\site\SiteGroupComponent;

return [

    SiteGroupComponent::OPTION_CLASS => 'umicms\hmvc\component\site\SiteGroupComponent',

    SiteGroupComponent::OPTION_COMPONENTS => [
        'survey' => '{#lazy:~/project/module/survey/site/survey/component.config.php}',
        'answer' => '{#lazy:~/project/module/survey/site/answer/component.config.php}'
    ],

    SiteGroupComponent::OPTION_VIEW => [
        'directories' => ['module/surveys'],
    ]
];