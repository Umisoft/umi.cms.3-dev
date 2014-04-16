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
use umicms\orm\collection\SimpleCollection;
use umicms\orm\collection\SimpleHierarchicCollection;
use umicms\project\admin\component\AdminComponent;

/**
 * Контроллер вывода настроек компонента
 */
class DefaultSettingsController extends BaseDefaultRestController implements IUrlManagerAware
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
     * Имя опции для задания действий в компоненте
     */
    const OPTION_INTERFACE_ACTIONS = 'actions';

    /**
     * @var array $defaultControls список контролов, используемых для управления простой коллекцией.
     */
    protected $defaultControls = [
        'filter' => [],
        'form' => [],
    ];

    /**
     * @var array $defaultHierarchicControls список контролов, используемых для управления иерархической коллекцией.
     */
    protected $defaultHierarchicControls = [
        'tree' => [],
        'children' => [],
        'filter' => [],
        'form' => [],
    ];

    /**
     * @var array $defaultLayout настройки интерфейса управления простой коллекцией
     */
    protected $defaultLayout = [
        'emptyContext' => [
            'contents' => [
                'controls' => ['filter']
            ]
        ],
        'selectedContext' => [
            'contents' => [
                'controls' => ['form']
            ]
        ]
    ];

    /**
     * @var array $defaultHierarchicLayout настройки интерфейса управления иерархической коллекцией
     */
    protected $defaultHierarchicLayout = [
        'emptyContext' => [
            'sideBar' => [
                'controls' => ['tree']
            ],
            'contents' => [
                'controls' => ['filter', 'children']
            ]
        ],
        'selectedContext' => [
            'sideBar' => [
                'controls' => ['tree']
            ],
            'contents' => [
                'controls' => ['form', 'children']
            ]
        ]
    ];

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
            self::OPTION_INTERFACE_CONTROLS => $this->buildControlsInfo($this->getControls()),
            self::OPTION_INTERFACE_LAYOUT => $this->buildLayoutInfo($this->getLayout()),
            self::OPTION_INTERFACE_ACTIONS => $this->buildActionsInfo()
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
     * Возвращает информацию об интерфейсном отображении контролов.
     * @param array $interfaceOptions настройки отображения контролов
     * @return array
     */
    protected function buildLayoutInfo(array $interfaceOptions)
    {
        return $interfaceOptions;
    }

    /**
     * Возвращает информацию о доступных действиях компонентов.
     * @return array
     */
    protected function buildActionsInfo()
    {
        $actions = [];
        /**
         * @var AdminComponent $component
         */
        $component = $this->getComponent();

        if ($component->hasController(AdminComponent::ACTION_CONTROLLER)) {
            $controller = $component->getController(AdminComponent::ACTION_CONTROLLER);
            if ($controller instanceof DefaultRestActionController) {

                foreach ($controller->getQueryActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'query',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
                    ];
                }

                foreach ($controller->getModifyActions() as $actionName) {
                    $actions[$actionName] = [
                        'type' => 'modify',
                        'displayName' => $this->translate('action:' . $actionName . ':displayName'),
                        'source' => $this->getUrlManager()->getAdminComponentActionResourceUrl($component, $actionName)
                    ];
                }
            }
        }

        return $actions;
    }

    /**
     * Возвращает список контролов, используемых для управления коллекцией.
     * @return array
     */
    protected function getControls()
    {
        $collection = $this->getCollection();
        if ($collection instanceof SimpleCollection) {
            return $this->defaultControls;
        }
        if ($collection instanceof SimpleHierarchicCollection) {
            return $this->defaultHierarchicControls;
        }

        return [];
    }

    /**
     * Возвращает настройки интерфейса управления коллекцией.
     * @return array
     */
    protected function getLayout()
    {
        $result = [];
        $collection = $this->getCollection();
        if ($collection instanceof SimpleCollection) {
            $result = $this->defaultLayout;
        }
        if ($collection instanceof SimpleHierarchicCollection) {
            $result = $this->defaultHierarchicLayout;
        }

        $result['collection'] = $collection->getName();

        return $result;
    }
}
 