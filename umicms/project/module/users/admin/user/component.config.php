<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\admin\user;

use umicms\hmvc\component\admin\collection\CollectionComponent;
use umicms\project\module\users\model\collection\UserCollection;

return [
    CollectionComponent::OPTION_CLASS => 'umicms\hmvc\component\admin\collection\CollectionComponent',
    CollectionComponent::OPTION_COLLECTION_NAME => 'user',

    CollectionComponent::OPTION_CONTROLLERS => [
        CollectionComponent::ACTION_CONTROLLER => __NAMESPACE__ . '\controller\ActionController',
        CollectionComponent::INTERFACE_LAYOUT_CONTROLLER => __NAMESPACE__ . '\controller\LayoutController',
    ],

    CollectionComponent::OPTION_QUERY_ACTIONS => [
        UserCollection::ACTION_GET_CHANGE_PASSWORD_FORM => []
    ],

    CollectionComponent::OPTION_MODIFY_ACTIONS => [
        UserCollection::ACTION_CHANGE_PASSWORD => []
    ]
];
