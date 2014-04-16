<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\component;

use umi\acl\IAclFactory;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\TCollectionManagerAware;
use umi\route\IRouteFactory;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\project\admin\component\AdminComponent;

/**
 * Компонент административной панели.
 */
class DefaultAdminComponent extends AdminComponent implements ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Опция для задания имени коллекции, с которой работает компонент.
     */
    const OPTION_COLLECTION_NAME = 'collectionName';

    public $defaultOptions = [

        DefaultAdminComponent::OPTION_CONTROLLERS => [
            DefaultAdminComponent::LIST_CONTROLLER =>  'umicms\project\admin\api\controller\DefaultRestListController',
            DefaultAdminComponent::ITEM_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestItemController',
            DefaultAdminComponent::ACTION_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestActionController',
            DefaultAdminComponent::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestSettingsController',
        ],

        DefaultAdminComponent::OPTION_ACL => [

            IAclFactory::OPTION_ROLES => [
                'editor' => []
            ],
            IAclFactory::OPTION_RESOURCES => [
                'controller:settings',
                'controller:action',
                'controller:item',
                'controller:list'
            ],
            IAclFactory::OPTION_RULES => [
                'editor' => [
                    'controller:settings' => [],
                    'controller:action' => [],
                    'controller:item' => [],
                    'controller:list' => []
                ],
            ]
        ],

        DefaultAdminComponent::OPTION_ROUTES      => [

            'action' => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'route'    => '/action/{action}',
                'defaults' => [
                    'controller' => DefaultAdminComponent::ACTION_CONTROLLER
                ]
            ],

            'collection' => [
                'type'     => IRouteFactory::ROUTE_FIXED,
                'route'    => '/collection',
                'subroutes' => [
                    'item' => [
                        'type'     => IRouteFactory::ROUTE_SIMPLE,
                        'route'    => '/{id:integer}',
                        'defaults' => [
                            'controller' => DefaultAdminComponent::ITEM_CONTROLLER
                        ]
                    ],
                    'list' => [
                        'type'     => IRouteFactory::ROUTE_SIMPLE,
                        'defaults' => [
                            'controller' => DefaultAdminComponent::LIST_CONTROLLER
                        ]
                    ]
                ]
            ],

            'settings' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => DefaultAdminComponent::SETTINGS_CONTROLLER
                ]
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name, $path, array $options = [])
    {
        $options = $this->mergeConfigOptions($this->defaultOptions, $options);
        parent::__construct($name, $path, $options);
    }

    /**
     * Возвращает коллекцию, с которой работает компонент.
     * @throws RuntimeException если в конфигурации не указано имя коллекции
     * @return ICmsCollection
     */
    public function getCollection()
    {
        if (!isset($this->options[self::OPTION_COLLECTION_NAME])) {
            throw new RuntimeException(
                $this->translate(
                    'Option "{option}" is required for component "{path}".',
                    [
                        'option' => self::OPTION_COLLECTION_NAME,
                        'path' => $this->getPath()
                    ]
                )
            );
        }

        return $this->getCollectionManager()->getCollection($this->options[self::OPTION_COLLECTION_NAME]);
    }
}
 