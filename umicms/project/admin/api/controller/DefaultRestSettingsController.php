<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
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
            'layout'         => $this->buildLayoutInfo($collection),
            'actions'        => $this->buildActionsInfo(),
            'filters'        => $this->buildCollectionFiltersInfo($collection)
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

        foreach ($controls as $controlName => $options) {
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
    protected function buildContentsInfo(ICmsCollection $collection)
    {

        return [
            'emptyContext'    => $this->buildEmptyContextInfo($collection),
            'selectedContext' => $this->buildSelectedContextInfo($collection)
        ];
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) не выбран.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildEmptyContextInfo(ICmsCollection $collection)
    {
        $result = [
            'filter'     => $this->buildFilterControlInfo($collection),
            'createForm' => $this->buildCreateFormControlInfo($collection)
        ];

        return $result;
    }

    /**
     * Возвращает информацию о контентной области, когда контекст (объект) выбран.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildSelectedContextInfo(ICmsCollection $collection)
    {
        $result = [
            'editForm'   => $this->buildEditFormControlInfo($collection),
            'createForm' => $this->buildCreateFormControlInfo($collection)
        ];

        return $result;
    }

    /**
     * Возвращает информацию о контроле "Форма редактирования"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildEditFormControlInfo(ICmsCollection $collection)
    {
        $result = [];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildSimpleButtonInfo('switchActivity');
        }

        if ($collection instanceof IRecyclableCollection) {
            $result['toolbar'][] = $this->buildSimpleButtonInfo('trash');
        } else {
            $result['toolbar'][] = $this->buildSimpleButtonInfo('delete');
        }

        if ($collection instanceof IRecoverableCollection && $collection->isBackupEnabled()) {
            $result['toolbar'][] = $this->buildSimpleButtonInfo('backupList');
        }

        return $result;
    }

    /**
     * Возвращает информацию о контроле "Форма создания"
     * @return array
     */
    protected function buildCreateFormControlInfo()
    {
        return [];
    }

    /**
     * Возвращает информацию о кнопке в тулбаре формы редактирования
     * @param string $name имя кнопки
     * @param bool $useIcon использовать ли иконку
     * @param bool $useLabel выводить ли label
     * @param null|string $class уникальный класс кнопки
     * @param array $params параметры обработчика
     * @return array
     */
    protected function buildSimpleButtonInfo($name, $useIcon = true, $useLabel = true, $class = null, array $params = [])
    {
        $label =  $this->translate('button:' . $name);

        $attributes = [
            'title' => $label,
            'class' => $class ?: 'button secondary'
        ];

        if ($useLabel) {
            $attributes['label'] = $label;
        }

        if ($useIcon) {
            $attributes['icon'] = [
                'class' => 'icon icon-' . $name
            ];
        }

        $behaviour = ['name' => $name];

        if ($params) {
            $behaviour = $behaviour + $params;
        }

        return [
            'type'        => 'button',
            'behaviour'   => $behaviour,
            'attributes' => $attributes
        ];
    }

    /**
     * Возвращает информацию о контроле "Фильтр"
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildFilterControlInfo(ICmsCollection $collection)
    {
        $result = [
            'displayName' => $this->translate('control:filter:displayName'),
            'toolbar'     => []
        ];

        if ($createButton = $this->buildCreateButtonInfo($collection)) {
            $result['toolbar'][] = $createButton;
        }

        /*
        if ($collection instanceof IActiveAccessibleCollection) {
            $result['toolbar'][] = $this->buildToolbarButtonInfo('switchActivity');
        }

        if ($collection instanceof ICmsPageCollection) {
            $result['toolbar'][] = $this->buildToolbarButtonInfo('viewOnSite');
        }
        */

        return $result;
    }


    /**
     * Возвращает информацию о кнопке в тулбаре формы создания
     * @param string $buttonType тип кнопки
     * @return array
     */
    protected function buildFilterToolButtonInfo($buttonType)
    {
        return [
            'type'        => $buttonType,
            'displayName' => $this->translate('control:filter:toolbar:' . $buttonType)
        ];
    }

    /**
     * Возвращает информацию о дереве компонента.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildSideBarInfo(ICmsCollection $collection)
    {

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
    protected function buildTreeControlInfo(SimpleHierarchicCollection $collection)
    {
        $actionList = [] ;$this->buildTreeControlCreateButtons($collection);

        if ($collection instanceof IActiveAccessibleCollection) {
            $actionList[] = $this->buildSimpleChoiceAction('switchActivity');
        }

        if ($collection instanceof ICmsPageCollection) {
            $actionList[] = $this->buildSimpleChoiceAction('viewOnSite');
        }

        return [
            'displayName' => $this->translate('control:tree:displayName'),
            'toolbar'     => [
                [
                    'type' => 'dropDownButton',
                    'attributes' => [
                        'class' => 'umi-button umi-toolbar-create-button'
                    ],
                    'choices' => $actionList
                ]
            ]
        ];
    }

    /**
     * Возвращает информацию о кнопке создания объектов в фильтре компонента.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildCreateButtonInfo(ICmsCollection $collection) {
        $typeList = $this->getCreateTypeList($collection);
        $typesCount = count($typeList);

        if (!$typesCount) {
            return [];
        }

        $createLabel = $this->translate('action:create');

        if ($typesCount == 1) {
            $label = $this->translate(
                '{createLabel} {typeCreateLabel}',
                [
                    'createLabel'     => $createLabel,
                    'typeCreateLabel' => $this->translate('type:' . $typeList[0] . ':createLabel')
                ]
            );
            return [
                'type' => 'button',
                'behaviour' => [
                    'name' => 'create',
                    'typeName' => $typeList[0]
                ],
                'attributes' => [
                    'class' => 'button primary',
                    'title' => $label,
                    'label'     => $label
                ]
            ];
        }

        if ($typesCount > 0) {
            $list = [];
            foreach ($typeList as $typeName) {
                $list[] = [
                    'behaviour'   => 'create',
                    'displayName' => $this->translate('type:' . $typeName . ':createLabel'),
                    'typeName'    => $typeName
                ];
            }
            return [
                'type' => 'dropDownButton',
                'attributes' => [
                    'class' => 'button dropdown primary',
                    'title' => $createLabel,
                    'label' => $createLabel . '...'
                ],
                'choices' => $this->buildCreateChoiceList($typeList)
            ];
        }

        return [];
    }

    /**
     * Возвращает информацию о списке создания
     * @param array $typeList
     * @return array
     */
    protected function buildCreateChoiceList(array $typeList)
    {
        $list = [];

        foreach ($typeList as $typeName) {
            $label = $this->translate('type:' . $typeName . ':createLabel');
            $list[] = [
                'behaviour'   => [
                    'name' => 'create',
                    'typeName' => $typeName
                 ],
                'attributes' => [
                    'title' => $label,
                    'label' => $label
                ]
            ];
        }

        return $list;
    }

    /**
     * Возвращает информацию для простого списочного действия
     * @param $name
     * @return array
     */
    protected function buildSimpleChoiceAction($name) {
        $label = $this->translate('action:' . $name);
        return [
            'behaviour' => [
                'name' => $name
            ],
            'attributes' => [
                'title' => $label,
                'label' => $label
            ]

        ];
    }

    /**
     * Возвращает информацию о кнопках создания в дереве компонента.
     * @param SimpleHierarchicCollection $collection
     * @return array
     */
    protected function buildTreeControlCreateButtons(SimpleHierarchicCollection $collection) {
        $createLabel = $this->translate('control:tree:toolbar:create');

        $result = [];
        foreach ($this->getCreateTypeList($collection) as $typeName) {
            $result[] = [
                'behaviour'   => 'create',
                'displayName' => $this->translate(
                    '{createLabel} {typeCreateLabel}',
                    [
                        'createLabel'     => $createLabel,
                        'typeCreateLabel' => $this->translate('type:' . $typeName . ':createLabel')
                    ]
                ),
                'typeName'    => $typeName
            ];
        }

        return $result;
    }

    /**
     * Возвращает информацию о кнопке в тулбаре дерева
     * @param string $behaviour обработчик
     * @return array
     */
    protected function buildTreeToolButtonInfo($behaviour)
    {
        return [
            'behaviour'   => $behaviour,
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
    protected function buildTreeFilterInfo($fieldName, array $values, $isActive = false)
    {

        return [
            'fieldName'   => $fieldName,
            'isActive'    => $isActive,
            'displayName' => $this->translate('control:tree:filter:' . $fieldName),
            'allow'       => $values
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
                'type'   => 'query',
                'source' => $this->getUrlManager()
                        ->getAdminComponentActionResourceUrl($component, $actionName)
            ];
        }

        foreach ($component->getModifyActions() as $actionName) {
            $actions[$actionName] = [
                'type'   => 'modify',
                'source' => $this->getUrlManager()
                        ->getAdminComponentActionResourceUrl($component, $actionName)
            ];
        }

        return $actions;
    }

    /**
     * Возвращает информацию о возможных фильтрах коллекции.
     * @param ICmsCollection $collection
     * @return array
     */
    protected function buildCollectionFiltersInfo(ICmsCollection $collection)
    {
        $result = [];

        if ($collection instanceof IActiveAccessibleCollection) {
            $result[] = $this->buildCollectionFilterInfo(IActiveAccessibleObject::FIELD_ACTIVE, [true]);
        }

        if ($collection instanceof StructureElementCollection) {
            $types = $collection->getMetadata()
                ->getTypesList();
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
    protected function buildCollectionFilterInfo($fieldName, array $values, $isActive = false)
    {

        return [
            'fieldName'   => $fieldName,
            'isActive'    => $isActive,
            'displayName' => $this->translate('filter:' . $fieldName),
            'allow'       => $values
        ];
    }

    /**
     * Возвращает список имен типов, доступных для создания
     * @param ICmsCollection $collection
     * @return array
     */
    protected function getCreateTypeList(ICmsCollection $collection) {
        $typeNames = array_merge(
            [IObjectType::BASE],
            $collection->getMetadata()
                ->getDescendantTypesList()
        );

        $result = [];
        foreach ($typeNames as $typeName) {
            if ($collection->hasForm(ICmsCollection::FORM_CREATE, $typeName)) {
                $result[] = $typeName;
            }
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function getI18nDictionaryNames()
    {
        $dictionaries = parent::getI18nDictionaryNames();
        $dictionaries = array_merge(
            $dictionaries,
            $this->getComponent()
                ->getCollection()
                ->getDictionaryNames()
        );

        return $dictionaries;
    }

}