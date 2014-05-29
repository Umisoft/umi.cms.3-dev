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
use umicms\project\module\structure\api\object\SystemPage;

/**
 * Контроллер вывода настроек компонента
 */
class DefaultRestSettingsController extends BaseDefaultRestController
{
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
            'actions' => $this->buildActionsInfo(),
            'filters' => $this->buildCollectionFiltersInfo($collection)
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
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildLayoutInfo(ICmsCollection $collection)
    {
        $layout = [];
        $layout['sideBar'] = $this->buildSideBarInfo($collection);

        $layout['contents'] = $this->buildContentsInfo($collection);

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

        return $result;
    }


    /**
     * Возвращает информацию о контроле "Форма редактирования"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildEditFormControlInfo(ICmsCollection $collection) {
        $result = [];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('switchActivity', 'buttonSwitchActivity');
        }

        if ($collection instanceof IRecyclableCollection) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('trash');
        } else {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('delete');
        }

        if ($collection instanceof IRecoverableCollection && $collection->isBackupEnabled()) {
            $result['toolbar'][] = $this->buildEditFormButtonInfo('backupList', 'buttonBackupList');
        }

        return $result;
    }

    /**
     * Возвращает информацию о контроле "Форма создания"
     * @return array
     */
    protected function buildCreateFormControlInfo() {
        return [];
    }

    /**
     * Возвращает информацию о кнопке в тулбаре формы редактирования
     * @param string $behaviour обработчик
     * @param string $type тип кнопки
     * @return array
     */
    protected function buildEditFormButtonInfo($behaviour, $type = 'button') {
        return [
            'type' => $type,
            'behaviour' => $behaviour,
            'displayName' => $this->translate('control:editForm:toolbar:' . $behaviour)
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
                $this->buildTreeToolButtonInfo('create')
            ]
        ];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('switchActivity');
        }

        if ($collection instanceof ICmsPageCollection) {
            $result['toolbar'][] = $this->buildTreeToolButtonInfo('viewOnSite');
        }

        return $result;
    }

    /**
     * Возвращает информацию о кнопках создания объектов коллекции
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildCreateButtonsInfo(ICmsCollection $collection) {
        $typeNames = [IObjectType::BASE];

        $typeNames = array_merge($typeNames, $collection->getMetadata()->getDescendantTypesList());

        $result = [];

        $createLabel = $this->translate('control:tree:toolbar:create');
        foreach ($typeNames as $typeName) {
            if ($collection->hasForm(ICmsCollection::FORM_CREATE, $typeName)) {
                $result[] = [
                    'type' => 'create',
                    'displayName' =>  $this->translate('{createLabel} "{typeDisplayName}"', [
                                'createLabel' => $createLabel,
                                'typeDisplayName' => $this->translate('type:' . $typeName . ':displayName')
                            ]),
                    'typeName' => $typeName
                ];
            }
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
        $toolbar = $this->buildCreateButtonsInfo($collection);

        if ($collection instanceof IActiveAccessibleCollection) {
            $toolbar[] = $this->buildTreeToolButtonInfo('switchActivity');
        }

        if ($collection instanceof ICmsPageCollection) {
            $toolbar[] = $this->buildTreeToolButtonInfo('viewOnSite');
        }

        return [
            'displayName' => $this->translate('control:tree:displayName'),
            'toolbar' => $toolbar
        ];
    }

    /**
     * Возвращает информацию о кнопке в тулбаре дерева
     * @param string $behaviour обработчик
     * @return array
     */
    protected function buildTreeToolButtonInfo($behaviour) {
        return [
            'behaviour' => $behaviour,
            'displayName' => $this->translate('control:tree:toolbar:' . $behaviour)
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

    /**
     * Возвращает информацию о возможных фильтрах коллекции.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildCollectionFiltersInfo(ICmsCollection $collection) {
        $result = [];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result[] = $this->buildCollectionFilterInfo(IActiveAccessibleObject::FIELD_ACTIVE, [true]);
        }

        if ($collection instanceof StructureElementCollection) {
            $types = $collection->getMetadata()->getTypesList();
            $types = array_values(array_diff($types, [SystemPage::TYPE]));
            $result[] = $this->buildCollectionFilterInfo(ICmsObject::FIELD_TYPE, $types, true);
        }

        return $result;
    }

    /**
     * Возвращает информацию о фильтре по полю для дерева
     * @param string $fieldName имя поля
     * @param array $values список разрешенных значений
     * @param bool $isActive активен ли фильтр
     * @return array
     */
    protected function buildCollectionFilterInfo($fieldName, array $values, $isActive = false) {

        return [
            'fieldName' => $fieldName,
            'isActive' => $isActive,
            'displayName' => $this->translate('filter:' . $fieldName),
            'allow' => $values
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getI18nDictionaryNames()
    {
        $dictionaries = parent::getI18nDictionaryNames();
        $dictionaries = array_merge($dictionaries, $this->getComponent()->getCollection()->getDictionaryNames());

        return $dictionaries;
    }

}