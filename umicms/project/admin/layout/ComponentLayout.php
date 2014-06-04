<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout;

use umicms\project\admin\layout\action\Action;
use umicms\project\admin\layout\control\CollectionControl;

/**
 * Билдер сетки для произвольного компонента.
 */
class ComponentLayout
{
    /**
     * @var array $params список дополнительных параметров для layout
     */
    public $params = [];

    /**
     * @var CollectionControl[] $sideBarControls список контролов для Sidebar
     */
    private $sideBarControls = [];
    /**
     * @var CollectionControl[] $emptyContextControls список контролов для контентной области, когда контекст не выбран
     */
    private $emptyContextControls = [];
    /**
     * @var CollectionControl[] $selectedContextControls список контролов для контентной области, когда контекст выбран
     */
    private $selectedContextControls = [];
    /**
     * @var Action[] $actions список доступных REST-действий
     */
    private $actions = [];

    /**
     * Добавляет контрол в Sidebar
     * @param string $name имя контрола
     * @param CollectionControl $control
     * @return $this
     */
    public function addSideBarControl($name, CollectionControl $control)
    {
        $this->sideBarControls[$name] = $control;

        return $this;
    }

    /**
     * Удаляет контрол из Sidebar
     * @param string $name имя контрола
     * @return $this
     */
    public function removeSideBarControl($name)
    {
        unset($this->sideBarControls[$name]);

        return $this;
    }

    /**
     * Добавляет контрол в контентную область для  пустого контекста
     * @param string $name имя контрола
     * @param CollectionControl $control
     * @return $this
     */
    public function addEmptyContextControl($name, CollectionControl $control)
    {
        $this->emptyContextControls[$name] = $control;

        return $this;
    }

    /**
     * Удаляет контрол из контентной области для пустого контекста
     * @param string $name
     * @return $this
     */
    public function removeEmptyContextControl($name)
    {
        unset($this->emptyContextControls[$name]);

        return $this;
    }

    /**
     * Добавляет контрол в контентную область для выбранного контекста
     * @param string $name имя контрола
     * @param CollectionControl $control
     * @return $this
     */
    public function addSelectedContextControl($name, CollectionControl $control)
    {
        $this->selectedContextControls[$name] = $control;

        return $this;
    }

    /**
     * Удаляет контрол из контентной области для выбранного контекста
     * @param string $name
     * @return $this
     */
    public function removeSelectedContextControl($name)
    {
        unset($this->selectedContextControls[$name]);

        return $this;
    }

    /**
     * Добавляет REST-действие
     * @param string $name
     * @param Action $action
     * @return $this
     */
    public function addAction($name, Action $action)
    {
        $this->actions[$name] = $action;

        return $this;
    }

    /**
     * Удаляет REST-действие
     * @param string $name
     * @return $this
     */
    public function removeAction($name)
    {
        unset($this->actions[$name]);

        return $this;
    }



    /**
     * Возвращает информацию о сетке.
     * @return array
     */
    public function build()
    {
        $sideBar = [];
        foreach ($this->sideBarControls as $name => $control) {
            $sideBar[$name] = $control->build();
        }

        $emptyContext = [];
        foreach ($this->emptyContextControls as $name => $control) {
            $emptyContext[$name] = $control->build();
        }

        $selectedContext = [];
        foreach ($this->selectedContextControls as $name => $control) {
            $selectedContext[$name] = $control->build();
        }

        $actions = [];
        foreach ($this->actions as $name => $action) {
            $actions[$name] = $action->build();
        }

        return [
            'params' => $this->params,
            'sideBar' => $sideBar,
            'contents' => [
                'emptyContext' => $emptyContext,
                'selectedContext' => $selectedContext
            ],
            'actions' => $actions
        ];
    }
}
 