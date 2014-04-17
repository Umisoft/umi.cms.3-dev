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
use umicms\hmvc\component\ICollectionComponent;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\project\admin\component\AdminComponent;

/**
 * Компонент административной панели.
 */
class DefaultAdminComponent extends AdminComponent implements ICollectionComponent, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Опция для задания дополнительного списка доступных действий на запрос данных
     */
    const OPTION_QUERY_ACTIONS = 'queryActions';
    /**
     * Опция для задания дополнительного списка доступных действий на изменение данных
     */
    const OPTION_MODIFY_ACTIONS = 'modifyActions';

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::LIST_CONTROLLER =>  'umicms\project\admin\api\controller\DefaultRestListController',
            self::ITEM_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestItemController',
            self::ACTION_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestActionController',
            self::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestSettingsController',
        ],

        self::OPTION_ACL => [

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

        self::OPTION_ROUTES      => [

            'action' => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'route'    => '/action/{action}',
                'defaults' => [
                    'controller' => self::ACTION_CONTROLLER
                ]
            ],

            'collection' => [
                'type'     => IRouteFactory::ROUTE_FIXED,
                'route'    => '/collection',
                'defaults' => [
                    'controller' => self::LIST_CONTROLLER
                ],
                'subroutes' => [
                    'item' => [
                        'type'     => IRouteFactory::ROUTE_SIMPLE,
                        'route'    => '/{id:integer}',
                        'defaults' => [
                            'controller' => self::ITEM_CONTROLLER
                        ]
                    ]
                ]
            ],

            'settings' => [
                'type' => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => self::SETTINGS_CONTROLLER
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
     * {@inheritdoc}
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

    /**
     * Возвращает список доступных действий на запрос данных.
     * @return array
     */
    public function getQueryActions()
    {
        $defaultActions = [
            'form'
        ];

        $collection = $this->getCollection();
        if ($collection instanceof IRecoverableCollection) {
            $defaultActions[] = 'backupList';
            $defaultActions[] = 'backup';
        }

        if ($collection instanceof ICmsPageCollection) {
            $defaultActions[] = 'viewOnSite';
        }

        $actions = [];
        if (isset($this->options[self::OPTION_QUERY_ACTIONS])) {
            $actions = $this->configToArray($this->options[self::OPTION_QUERY_ACTIONS]);
        }

        return array_merge($defaultActions, $actions);
    }

    /**
     * Возвращает список доступных действий на изменение данных.
     * @return array
     */
    public function getModifyActions()
    {
        $defaultActions = [];
        $collection = $this->getCollection();

        if ($collection instanceof IActiveAccessibleCollection) {
            $defaultActions[] = 'switchActivity';
        }
        if ($collection instanceof SimpleHierarchicCollection) {
            $defaultActions[] = 'move';
        }
        if ($collection instanceof ICmsPageCollection) {
            $defaultActions[] = 'changeSlug';
        }
        if ($collection instanceof IRecyclableCollection) {
            $defaultActions[] = 'trash';
            $defaultActions[] = 'untrash';
        }

        $actions = [];
        if (isset($this->options[self::OPTION_MODIFY_ACTIONS])) {
            $actions = $this->configToArray($this->options[self::OPTION_MODIFY_ACTIONS]);
        }

        return array_merge($defaultActions, $actions);
    }


}
 