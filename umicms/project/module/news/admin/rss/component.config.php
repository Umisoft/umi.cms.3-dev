<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\admin\rss;

use umicms\project\admin\rest\component\CollectionApiComponent;

return [
    CollectionApiComponent::OPTION_CLASS => 'umicms\project\admin\rest\component\CollectionApiComponent',
    CollectionApiComponent::OPTION_COLLECTION_NAME => 'newsRssImportScenario',

    CollectionApiComponent::OPTION_CONTROLLERS => [
        CollectionApiComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        CollectionApiComponent::INTERFACE_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController'
    ],

    CollectionApiComponent::OPTION_MODIFY_ACTIONS => [
        'importFromRss'
    ]
];
