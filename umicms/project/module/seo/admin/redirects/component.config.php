<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\seo\admin\redirects;

use umicms\hmvc\component\admin\collection\CollectionComponent;

return [
    CollectionComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\collection\CollectionComponent',
    CollectionComponent::OPTION_COLLECTION_NAME => 'redirects',

    CollectionComponent::OPTION_CONTROLLERS => [
        CollectionComponent::ITEM_CONTROLLER => __NAMESPACE__ . '\controller\ItemController',
        CollectionComponent::LIST_CONTROLLER => __NAMESPACE__ . '\controller\ListController',
    ],
];
