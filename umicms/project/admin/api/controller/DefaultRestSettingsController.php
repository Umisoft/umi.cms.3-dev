<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\component\DefaultAdminComponent;
use umicms\project\module\structure\api\collection\StructureElementCollection;

/**
 * Контроллер вывода настроек компонента
 */
class DefaultRestSettingsController extends BaseDefaultRestController implements IUrlManagerAware
{
    use TUrlManagerAware;

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
        return [
            'collectionName' => $this->getCollectionName(),
            'layout' => $this->buildLayoutInfo(),
            'actions' => $this->buildActionsInfo()
        ];
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
     * @return array
     */
    protected function buildLayoutInfo()
    {
        $layout = [];
        $collection = $this->getCollection();
        $layout[self::OPTION_INTERFACE_LAYOUT_SIDEBAR] = $this->buildSideBarInfo($collection);

        $layout[self::OPTION_INTERFACE_LAYOUT_CONTENTS] = $this->buildContentsInfo($collection);

        return $layout;
    }

    /**
     * Возвращает информацию о контентной области компонента компонента.
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildContentsInfo(ICmsCollection $cmsCollection) {

        return [
            'emptyContext' => $this->buildEmptyContextInfo($cmsCollection),
            'selectedContext' => $this->buildSelectedContextInfo($cmsCollection)
        ];
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) не выбран.
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildEmptyContextInfo(ICmsCollection $cmsCollection) {
        $result =  [
            'filter' => $this->buildFilterControlInfo($cmsCollection)
        ];

        if ($cmsCollection instanceof SimpleHierarchicCollection) {
            $result['children'] = $this->buildChildrenControlInfo($cmsCollection);
        }

        return $result;
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) выбран.
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildSelectedContextInfo(ICmsCollection $cmsCollection) {
        $result =  [
            'editForm' => $this->buildEditFormControlInfo($cmsCollection),
            'createForm' => $this->buildCreateFormControlInfo($cmsCollection)
        ];

        if ($cmsCollection instanceof SimpleHierarchicCollection) {
            $result['children'] = $this->buildChildrenControlInfo($cmsCollection);
        }

        return $result;
    }


    /**
     * Возвращает информацию о контроле "Форма редактирования"
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildEditFormControlInfo(ICmsCollection $cmsCollection) {
        return [
            'displayName' => $this->translate('control:editForm:displayName')
        ];
    }

    /**
     * Возвращает информацию о контроле "Форма создания"
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildCreateFormControlInfo(ICmsCollection $cmsCollection) {
        return [
            'displayName' => $this->translate('control:createForm:displayName')
        ];
    }

    /**
     * Возвращает информацию о контроле "Фильтр"
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildFilterControlInfo(ICmsCollection $cmsCollection) {
        return [
            'displayName' => $this->translate('control:filter:displayName')
        ];
    }

    /**
     * Возвращает информацию о контроле "Дочерние объекты"
     * @param ICmsCollection $cmsCollection
     * @return array
     */
    protected function buildChildrenControlInfo(ICmsCollection $cmsCollection) {
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

    protected function buildTreeControlInfo(SimpleHierarchicCollection $collection) {
        $tree = [
            'displayName' => $this->translate('control:tree:displayName'),
            'toolbar' => [
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_CREATE_FORM),
                $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_GET_EDIT_FORM)
            ],
            'filters' => []
        ];

        if ($collection instanceof IActiveAccessibleCollection) {
            $tree['toolbar'][] = $this->buildTreeToolButtonInfo(DefaultAdminComponent::ACTION_SWITCH_ACTIVITY);
            $tree['filters'][] = $this->buildTreeFilterInfo(IActiveAccessibleObject::FIELD_ACTIVE, [true]);
        }

        if ($collection instanceof StructureElementCollection) {
            $types = $collection->getMetadata()->getTypesList();
            $types = array_values(array_diff($types, [StructureElementCollection::TYPE_SYSTEM]));
            $tree['filters'][] = $this->buildTreeFilterInfo(ICmsObject::FIELD_TYPE, $types, true);
        }


        return $tree;
    }

    /**
     * Возвращает информацию о кнопке в тулбаре дерева
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildTreeToolButtonInfo($buttonType) {
        return [
            'type' => $buttonType,
            'displayName' => 'control:tree:toolbar:' . $buttonType
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
        $filedFilterNamespace = 'control:tree:filter:';

        return [
            'fieldName' => $fieldName,
            'isActive' => $isActive,
            'displayName' => $filedFilterNamespace . IActiveAccessibleObject::FIELD_ACTIVE,
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
 