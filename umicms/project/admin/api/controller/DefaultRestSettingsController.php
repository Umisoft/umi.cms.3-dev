<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\component\DefaultAdminComponent;
use umicms\project\module\structure\api\collection\StructureElementCollection;

/**
 * Контроллер вывода настроек компонента
 */
class DefaultRestSettingsController extends BaseDefaultRestController
{
    /**
     * Имя опции для задания контролов компонента.
     */
    const OPTION_INTERFACE_CONTROLS = 'controls';
    /**
     * Имя опции для задания настроек интерфейсного отображения контролов
     */
    const OPTION_INTERFACE_LAYOUT = 'layout';
    /**
     * Имя опции для задания SideBar части интерфейса
     */
    const OPTION_INTERFACE_LAYOUT_SIDEBAR = 'sideBar';
    /**
     * Имя опции для задания Contents части интерфейса
     */
    const OPTION_INTERFACE_LAYOUT_CONTENTS = 'contents';
    /**
     * Имя опции для задания действий в компоненте
     */
    const OPTION_INTERFACE_ACTIONS = 'actions';
    /**
     * Имя контрола "Форма редактирования"
     */
    const CONTROL_EDIT_FORM = 'editForm';
    /**
     * Имя контрола "Форма создания"
     */
    const CONTROL_CREATE_FORM = 'createForm';
    /**
     * Имя контрола "Список дочерних элементов"
     */
    const CONTROL_CHILDREN = 'createForm';
    /**
     * Имя контрола "Фильтр элементов"
     */
    const CONTROL_FILTER = 'createForm';


    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        return $this->createViewResponse(
            'settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    /**
     * Возвращает настройки компонента
     * @return array
     */
    protected function getSettings()
    {
        $collection = $this->getCollection();
        return [
            'collectionName' => $collection->getName(),
            'layout' => $this->buildLayoutInfo($collection),
            'types' => [$this->buildTypesTreeNode($collection)],
            'actions' => $this->buildActionsInfo()
        ];
    }

    /**
     * Возвращает дерево типов коллекции компонента
     * @param ICmsCollection $collection
     * @param string $typeName имя типа
     * @return array
     */
    protected function buildTypesTreeNode(ICmsCollection $collection, $typeName = IObjectType::BASE) {

        $result = [
            'name' => $typeName,
            'displayName' => $this->translate('type:' . $typeName . ':displayName'),

        ];

        if ($collection->hasForm(ICmsCollection::FORM_CREATE, $typeName)) {
            $result[ICmsCollection::FORM_CREATE] = $this->getUrlManager()->getAdminComponentActionResourceUrl(
                $this->getComponent(),
                DefaultAdminComponent::ACTION_GET_CREATE_FORM,
                ['type' => $typeName]
            );
        }

        if ($collection->hasForm(ICmsCollection::FORM_EDIT, $typeName)) {
            $result[ICmsCollection::FORM_EDIT] = $this->getUrlManager()->getAdminComponentActionResourceUrl(
                $this->getComponent(),
                DefaultAdminComponent::ACTION_GET_EDIT_FORM,
                ['type' => $typeName]
            );
        }

        $childTypes = [];
        foreach ($collection->getMetadata()->getChildTypesList($typeName) as $childTypeName) {
            $childTypes[] = $this->buildTypesTreeNode($collection, $childTypeName);
        }

        if ($childTypes) {
            $result['types'] = $childTypes;
        }

        return $result;
    }

    /**
     * Возвращает информацию о контролах компонента.
     * @param array $controls список контролов с опциями
     * @return array
     */
    protected function buildControlsInfo(array $controls)
    {
       $controlsInfo = [];

       foreach($controls as $controlName => $options) {
            $options['displayName'] = $this->translate('control:' . $controlName . ':displayName');
            $controlsInfo[$controlName] = $options;
        }

        return $controlsInfo;
    }

    /**
     * Возвращает информацию об интерфейсном отображении компонента.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildLayoutInfo(ICmsCollection $collection)
    {
        $layout = [];
        $layout[self::OPTION_INTERFACE_LAYOUT_SIDEBAR] = $this->buildSideBarInfo($collection);

        $layout[self::OPTION_INTERFACE_LAYOUT_CONTENTS] = $this->buildContentsInfo($collection);

        return $layout;
    }

    /**
     * Возвращает информацию о контентной области компонента компонента.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildContentsInfo(ICmsCollection $collection) {

        return [
            'emptyContext' => $this->buildEmptyContextInfo($collection),
            'selectedContext' => $this->buildSelectedContextInfo($collection)
        ];
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) не выбран.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildEmptyContextInfo(ICmsCollection $collection) {
        $result =  [
            'filter' => $this->buildFilterControlInfo($collection),
            'createForm' => $this->buildCreateFormControlInfo($collection)
        ];

        if ($collection instanceof SimpleHierarchicCollection) {
            $result['children'] = $this->buildChildrenControlInfo($collection);
        }

        return $result;
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) выбран.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildSelectedContextInfo(ICmsCollection $collection) {
        $result =  [
            'editForm' => $this->buildEditFormControlInfo($collection),
            'createForm' => $this->buildCreateFormControlInfo($collection)
        ];

        if ($collection instanceof SimpleHierarchicCollection) {
            $result['children'] = $this->buildChildrenControlInfo($collection);
        }

        return $result;
    }


    /**
     * Возвращает информацию о контроле "Форма редактирования"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildEditFormControlInfo(ICmsCollection $collection) {
        $result = [
            'displayName' => $this->translate('control:editForm:displayName'),
            'toolbar' => [
                $this->buildEditFormButtonInfo('apply')
            ]
        ];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('switchActivity');
        }

        if ($collection instanceof IRecyclableCollection) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo(DefaultAdminComponent::ACTION_TRASH);
        } else {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('delete');
        }

        if ($collection instanceof IRecoverableCollection && $collection->isBackupEnabled()) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('backupList');
        }

        return $result;
    }

    /**
     * Возвращает информацию о контроле "Форма создания"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildCreateFormControlInfo(ICmsCollection $collection) {
        return [
            'displayName' => $this->translate('control:createForm:displayName'),
            'toolbar' => [
                $this->buildEditFormButtonInfo('create')
            ]
        ];
    }

    /**
     * Возвращает информацию о кнопке в тулбаре формы редактирования
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildEditFormButtonInfo($buttonType) {
        return [
            'type' => $buttonType,
            'displayName' => $this->translate('control:editForm:toolbar:' . $buttonType)
        ];
    }

    /**
     * Возвращает информацию о кнопке в тулбаре формы создания
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildCreateFormButtonInfo($buttonType) {
        return [
            'type' => $buttonType,
            'displayName' => $this->translate('control:createForm:toolbar:' . $buttonType)
        ];
    }

    /**
     * Возвращает информацию о контроле "Фильтр"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildFilterControlInfo(ICmsCollection $collection) {
        $result = [
            'displayName' => $this->translate('control:filter:displayName'),
            'toolbar' => [
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_CREATE_FORM),
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_EDIT_FORM)
            ]
        ];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('switchActivity');
            $result['filters'][] = $this->buildTreeFilterInfo(IActiveAccessibleObject::FIELD_ACTIVE, [true]);
        }

        if ($collection instanceof ICmsPageCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('viewOnSite');
        }

        if ($collection instanceof StructureElementCollection) {
            $types = $collection->getMetadata()->getTypesList();
            $types = array_values(array_diff($types, [StructureElementCollection::TYPE_SYSTEM]));
            $result['filters'][] = $this->buildTreeFilterInfo(ICmsObject::FIELD_TYPE, $types, true);
        }

        return $result;
    }

    /**
     * Возвращает информацию о кнопке в тулбаре формы создания
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildFilterToolButtonInfo($buttonType) {
        return [
            'type' => $buttonType,
            'displayName' => $this->translate('control:filter:toolbar:' . $buttonType)
        ];
    }

    /**
     * Возвращает информацию о контроле "Дочерние объекты"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildChildrenControlInfo(ICmsCollection $collection) {
        return [
            'displayName' => $this->translate('control:children:displayName')
        ];
    }

    /**
     * Возвращает информацию о дереве компонента.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildSideBarInfo(ICmsCollection $collection) {

        if ($collection instanceof SimpleHierarchicCollection) {
            return [
                'tree' => $this->buildTreeControlInfo($collection)
            ];
        }

        return [];

    }

    /**
     * Возвращает информацию о дереве компонента.
     * @param SimpleHierarchicCollection $collection
     * @return array
     */
    protected function buildTreeControlInfo(SimpleHierarchicCollection $collection) {
        $result = [
            'displayName' => $this->translate('control:tree:displayName'),
            'toolbar' => [
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_CREATE_FORM),
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_EDIT_FORM)
            ],
            'filters' => []
        ];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('switchActivity');
            $result['filters'][] = $this->buildTreeFilterInfo(IActiveAccessibleObject::FIELD_ACTIVE, [true]);
        }

        if ($collection instanceof ICmsPageCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('viewOnSite');
        }

        if ($collection instanceof StructureElementCollection) {
            $types = $collection->getMetadata()->getTypesList();
            $types = array_values(array_diff($types, [StructureElementCollection::TYPE_SYSTEM]));
            $result['filters'][] = $this->buildTreeFilterInfo(ICmsObject::FIELD_TYPE, $types, true);
        }


        return $result;
    }

    /**
     * Возвращает информацию о кнопке в тулбаре дерева
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildTreeToolButtonInfo($buttonType) {
        return [
            'type' => $buttonType,
            'displayName' => $this->translate('control:tree:toolbar:' . $buttonType)
        ];
    }

    /**
     * Возвращает информацию о фильтре по полю для дерева
     * @param string $fieldName имя поля
     * @param array $values список разрешенных значений
     * @param bool $isActive активен ли фильтр
     * @return array
     */
    protected function buildTreeFilterInfo($fieldName, array $values, $isActive = false) {

        return [
            'fieldName' => $fieldName,
            'isActive' => $isActive,
            'displayName' => $this->translate('control:tree:filter:' . $fieldName),
            'allow' => $values
        ];
    }

    /**
     * Возвращает информацию о доступных действиях компонентов.
     * @return array
     */
    protected function buildActionsInfo()
    {
        $actions = [];
        $component = $this->getComponent();

        foreach ($component->getQueryActions() as $actionName) {
            $actions[$actionName] = [
                'type' => 'query',
                'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
            ];
        }

        foreach ($component->getModifyActions() as $actionName) {
            $actions[$actionName] = [
                'type' => 'modify',
                'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
            ];
        }

        return $actions;
    }

}
 