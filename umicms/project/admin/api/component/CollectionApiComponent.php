<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\layout\action\Action;

/**
 * Компонент административной панели.
 */
class CollectionApiComponent extends AdminComponent implements ICollectionComponent, ICollectionManagerAware
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
     * Действие для получения формы редактирования
     */
    const ACTION_GET_EDIT_FORM = 'getEditForm';
    /**
     * Действие для получения формы создания
     */
    const ACTION_GET_CREATE_FORM = 'getCreateForm';
    /**
     * Действие для получения списка резервных копий
     */
    const ACTION_GET_BACKUP_LIST = 'getBackupList';
    /**
     * Действие для получения бэкапа объекта
     */
    const ACTION_GET_BACKUP = 'getBackup';
    /**
     * Действие для активации объекта
     */
    const ACTION_ACTIVATE = 'activate';
    /**
     * Действие для деактивации объекта
     */
    const ACTION_DEACTIVATE = 'deactivate';
    /**
     * Действие для изменения ЧПУ объекта
     */
    const ACTION_CHANGE_SLUG = 'changeSlug';
    /**
     * Действие для перемещения объекта
     */
    const ACTION_MOVE = 'move';
    /**
     * Действие для удаления объекта в корзину
     */
    const ACTION_TRASH = 'trash';
    /**
     * Действие для восстановления объекта из корзины
     */
    const ACTION_UNTRASH = 'untrash';

    /**
     * @var array $defaultOptions настройки компонента по умолчанию
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::LIST_CONTROLLER     => 'umicms\project\admin\api\controller\DefaultRestListController',
            self::ITEM_CONTROLLER     => 'umicms\project\admin\api\controller\DefaultRestItemController',
            self::ACTION_CONTROLLER   => 'umicms\project\admin\api\controller\DefaultRestActionController',
            self::SETTINGS_CONTROLLER => 'umicms\project\admin\api\controller\DefaultRestSettingsController',
        ],
        self::OPTION_ACL         => [

            IAclFactory::OPTION_ROLES     => [
                'editor' => []
            ],
            IAclFactory::OPTION_RESOURCES => [
                'controller:settings',
                'controller:action',
                'controller:item',
                'controller:list'
            ],
            IAclFactory::OPTION_RULES     => [
                'editor' => [
                    'controller:settings' => [],
                    'controller:action'   => [],
                    'controller:item'     => [],
                    'controller:list'     => []
                ],
            ]
        ],
        self::OPTION_ROUTES      => [

            'action'     => [
                'type'     => IRouteFactory::ROUTE_SIMPLE,
                'route'    => '/action/{action}',
                'defaults' => [
                    'controller' => self::ACTION_CONTROLLER
                ]
            ],
            'collection' => [
                'type'      => IRouteFactory::ROUTE_FIXED,
                'route'     => '/collection',
                'defaults'  => [
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
            'settings'   => [
                'type'     => IRouteFactory::ROUTE_FIXED,
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
        $options = $this->mergeConfigOptions($options, $this->defaultOptions);
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
                        'path'   => $this->getPath()
                    ]
                )
            );
        }

        return $this->getCollectionManager()
            ->getCollection($this->options[self::OPTION_COLLECTION_NAME]);
    }

    /**
     * Возвращает список доступных действий на запрос данных.
     * @return Action[] массив вида [actionName => Action, ...]
     */
    public function getQueryActions()
    {
        $actions = [
            self::ACTION_GET_EDIT_FORM   => $this->createQueryAction(self::ACTION_GET_EDIT_FORM),
            self::ACTION_GET_CREATE_FORM => $this->createQueryAction(self::ACTION_GET_CREATE_FORM)
        ];

        $collection = $this->getCollection();
        if ($collection instanceof IRecoverableCollection) {
            $actions[self::ACTION_GET_BACKUP_LIST] = $this->createQueryAction(self::ACTION_GET_BACKUP_LIST);
            $actions[self::ACTION_GET_BACKUP_LIST] = $this->createQueryAction(self::ACTION_GET_BACKUP);
        }

        if (isset($this->options[self::OPTION_QUERY_ACTIONS])) {
            foreach ($this->options[self::OPTION_QUERY_ACTIONS] as $actionName) {
                $actions[$actionName] = $this->createQueryAction($actionName);
            }
        }

        return $actions;
    }

    /**
     * Возвращает список доступных действий на изменение данных.
     * @return Action[] массив вида [actionName => Action, ...]
     */
    public function getModifyActions()
    {
        $actions = [];
        $collection = $this->getCollection();

        if ($collection instanceof IActiveAccessibleCollection) {
            $actions[self::ACTION_ACTIVATE] = $this->createModifyAction(self::ACTION_ACTIVATE);
            $actions[self::ACTION_DEACTIVATE] = $this->createModifyAction(self::ACTION_DEACTIVATE);
        }
        if ($collection instanceof SimpleHierarchicCollection) {
            $actions[self::ACTION_MOVE] = $this->createModifyAction(self::ACTION_MOVE);
        }
        if ($collection instanceof ICmsPageCollection) {
            $actions[self::ACTION_CHANGE_SLUG] = $this->createModifyAction(self::ACTION_CHANGE_SLUG);
        }
        if ($collection instanceof IRecyclableCollection) {
            $actions[self::ACTION_TRASH] = $this->createModifyAction(self::ACTION_TRASH);
            $actions[self::ACTION_UNTRASH] = $this->createModifyAction(self::ACTION_UNTRASH);
        }

        if (isset($this->options[self::OPTION_MODIFY_ACTIONS])) {
            foreach ($this->options[self::OPTION_MODIFY_ACTIONS] as $actionName) {
                $actions[$actionName] = $this->createModifyAction($actionName);
            }
        }

        if (isset($this->options[self::OPTION_MODIFY_ACTIONS])) {
            $actions = $this->configToArray($this->options[self::OPTION_MODIFY_ACTIONS]);
        }

        return $actions;
    }

    /**
     * Создает новое REST-действие компонента для выборки данных
     * @param string $actionName имя действия
     * @return Action
     */
    protected function createQueryAction($actionName)
    {
        return new Action(
            $this->getUrlManager()->getAdminComponentActionResourceUrl($this, $actionName),
            Action::TYPE_QUERY
        );
    }

    /**
     * Создает новое REST-действие компонента для модификации данных
     * @param string $actionName имя действия
     * @return Action
     */
    protected function createModifyAction($actionName)
    {
        return new Action(
            $this->getUrlManager()->getAdminComponentActionResourceUrl($this, $actionName),
            Action::TYPE_MODIFY
        );
    }
}
 