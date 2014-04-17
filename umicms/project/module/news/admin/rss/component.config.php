<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\admin\rss;

use umicms\project\admin\api\component\DefaultAdminComponent;

return [
    DefaultAdminComponent::OPTION_CLASS => 'umicms\project\admin\api\component\DefaultAdminComponent',
    DefaultAdminComponent::OPTION_COLLECTION_NAME => 'newsRssImportScenario',

    DefaultAdminComponent::OPTION_CONTROLLERS => [
        DefaultAdminComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController'
    ],

    DefaultAdminComponent::OPTION_MODIFY_ACTIONS => [
        'importFromRss'
    ]
];
