<?php
namespace demohunt\site;

use umicms\route\SitePageRoute;
use umicms\site\SiteApplication;

return [
    SiteApplication::OPTION_ROUTES => [
        'sitePageRoute' => [
            SitePageRoute::OPTION_DEFAULTS => [
                SitePageRoute::OPTION_DEFAULT_PAGE => 'd534fd83-0f12-4a0d-9853-583b9181a948'
            ]
        ]
    ]
];