<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\collection;

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
use umicms\orm\collection\CmsHierarchicCollection;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Компонент административной панели для управления коллекцией.
 */
class CollectionComponent extends AdminComponent implements ICollectionComponent, ICollectionManagerAware
{
    use TCollectionManagerAware;

    /**
     * Контроллер для выполнения RUD-операций над объектом
     */
    const ITEM_CONTROLLER = 'item';
    /**
     * Контроллер для выполнения CRUD-операций над списком объектов
     */
    const LIST_CONTROLLER = 'list';
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
     * {@inheritdoc}
     */
    public $defaultOptions = [

        self::OPTION_CONTROLLERS => [
            self::LIST_CONTROLLER     => 'umicms\hmvc\component\admin\collection\ListController',
            self::ITEM_CONTROLLER     => 'umicms\hmvc\component\admin\collection\ItemController',
            self::ACTION_CONTROLLER   => 'umicms\hmvc\component\admin\collection\ActionController',
            self::INTERFACE_LAYOUT_CONTROLLER => 'umicms\hmvc\component\admin\collection\LayoutController',
        ],
        self::OPTION_ACL         => [

            IAclFactory::OPTION_ROLES     => [
                'editor' => []
            ],
            IAclFactory::OPTION_RULES     => [
                'editor' => [
                    'controller:action'   => [],
                    'controller:item'     => [],
                    'controller:list'     => [],
                    'controller:interfaceLayout'     => []
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
            'layout'   => [
                'type'     => IRouteFactory::ROUTE_FIXED,
                'defaults' => [
                    'controller' => self::INTERFACE_LAYOUT_CONTROLLER
                ]
            ]
        ]
    ];

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
     * {@inheritdoc}
     */
    public function getQueryActions()
    {
        $actions = parent::getQueryActions();

        $actions[self::ACTION_GET_EDIT_FORM] = $this->createQueryAction(
            self::ACTION_GET_EDIT_FORM,
            ['type' => '{type}']
        );
        $actions[self::ACTION_GET_CREATE_FORM] = $this->createQueryAction(
            self::ACTION_GET_CREATE_FORM,
            ['type' => '{type}']
        );

        $collection = $this->getCollection();
        if ($collection instanceof IRecoverableCollection) {
            $actions[self::ACTION_GET_BACKUP_LIST] = $this->createQueryAction(
                self::ACTION_GET_BACKUP_LIST,
                ['id' => '{id}']
            );
            $actions[self::ACTION_GET_BACKUP] = $this->createQueryAction(
                self::ACTION_GET_BACKUP,
                ['id' => '{objectId}', 'backupId' => '{id}']
            );
        }

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function getModifyActions()
    {
        $actions = parent::getModifyActions();

        $collection = $this->getCollection();

        if ($collection instanceof IActiveAccessibleCollection) {
            $actions[self::ACTION_ACTIVATE] = $this->createModifyAction(self::ACTION_ACTIVATE);
            $actions[self::ACTION_DEACTIVATE] = $this->createModifyAction(self::ACTION_DEACTIVATE);
        }
        if ($collection instanceof CmsHierarchicCollection) {
            $actions[self::ACTION_MOVE] = $this->createModifyAction(self::ACTION_MOVE);
        }
        if ($collection instanceof ICmsPageCollection) {
            $actions[self::ACTION_CHANGE_SLUG] = $this->createModifyAction(self::ACTION_CHANGE_SLUG);
        }
        if ($collection instanceof IRecyclableCollection) {
            $actions[self::ACTION_TRASH] = $this->createModifyAction(self::ACTION_TRASH);
            $actions[self::ACTION_UNTRASH] = $this->createModifyAction(self::ACTION_UNTRASH);
        }

        return $actions;
    }

}
 