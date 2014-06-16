<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout;

use umi\hmvc\component\IComponent;
use umicms\exception\OutOfBoundsException;
use umicms\hmvc\component\admin\AdminComponent;
use umicms\hmvc\component\admin\layout\action\Action;
use umicms\hmvc\component\admin\layout\control\AdminControl;

/**
 * Билдер сетки для произвольного административного компонента.
 */
class AdminComponentLayout
{
    /**
     * @var array $params список дополнительных параметров для layout
     */
    public $params = [];

    /**
     * @var IComponent $component
     */
    protected $component;
    /**
     * @var AdminControl[] $sideBarControls список контролов для Sidebar
     */
    private $sideBarControls = [];
    /**
     * @var AdminControl[] $emptyContextControls список контролов для контентной области, когда контекст не выбран
     */
    private $emptyContextControls = [];
    /**
     * @var AdminControl[] $selectedContextControls список контролов для контентной области, когда контекст выбран
     */
    private $selectedContextControls = [];
    /**
     * @var Action[] $actions список доступных REST-действий
     */
    private $actions = [];

    /**
     * Конструктор.
     * @param AdminComponent $component.
     */
    public function __construct(AdminComponent $component) {
        $this->component = $component;

        $this->configureActions();
        $this->configureSideBar();
        $this->configureEmptyContextControls();
        $this->configureSelectedContextControls();
    }

    /**
     * Добавляет контрол в Sidebar
     * @param string $name имя контрола
     * @param AdminControl $control
     * @return $this
     */
    public function addSideBarControl($name, AdminControl $control)
    {
        $this->sideBarControls[$name] = $control;

        return $this;
    }

    /**
     * Возвращает контрол из sidebar
     * @param string $name
     * @throws OutOfBoundsException если кнотрол не найден
     * @return AdminControl
     */
    public function getSideBarControl($name)
    {
        if (!isset($this->sideBarControls[$name])) {
            throw new OutOfBoundsException(sprintf('Sidebar control "%s" does not exist.', $name));
        }

        return $this->sideBarControls[$name];
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
     * @param AdminControl $control
     * @return $this
     */
    public function addEmptyContextControl($name, AdminControl $control)
    {
        $this->emptyContextControls[$name] = $control;

        return $this;
    }

    /**
     * Возвращает контрол из контентной области пустого контекста
     * @param string $name
     * @throws OutOfBoundsException если кнотрол не найден
     * @return AdminControl
     */
    public function getEmptyContextControl($name)
    {
        if (!isset($this->emptyContextControls[$name])) {
            throw new OutOfBoundsException(sprintf('Empty context control "%s" does not exist.', $name));
        }

        return $this->emptyContextControls[$name];
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
     * @param AdminControl $control
     * @return $this
     */
    public function addSelectedContextControl($name, AdminControl $control)
    {
        $this->selectedContextControls[$name] = $control;

        return $this;
    }


    /**
     * Возвращает контрол из контентной области выбранного контекста
     * @param string $name
     * @throws OutOfBoundsException если кнотрол не найден
     * @return AdminControl
     */
    public function getSelectedContextControl($name)
    {
        if (!isset($this->selectedContextControls[$name])) {
            throw new OutOfBoundsException(sprintf('Selected context control "%s" does not exist.', $name));
        }

        return $this->selectedContextControls[$name];
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

        $result = [];
        if ($this->params) {
            $result['params'] = $this->params;
        }

        if ($sideBar) {
            $result['sideBar'] = $sideBar;
        }
        $result['contents'] = [];

        if ($emptyContext) {
            $result['contents']['emptyContext'] = $emptyContext;
        }

        if ($selectedContext) {
            $result['contents']['selectedContext'] = $selectedContext;
        }

        if ($actions) {
            $result['actions'] = $actions;
        }

        return $result;
    }

    /**
     * Конфигурирует REST-экшены для компонента.
     * @return $this
     */
    protected function configureActions()
    {
        foreach ($this->component->getQueryActions() as $name => $action) {
            $this->addAction($name, $action);
        }

        foreach ($this->component->getModifyActions() as $name => $action) {
            $this->addAction($name, $action);
        }

        return $this;
    }

    /**
     * Конфигурирует контролы контентной области для пустого контекста.
     */
    protected function configureEmptyContextControls()
    {

    }

    /**
     * Конфигурирует контролы контентной области для выбранного контекста.
     */
    protected function configureSelectedContextControls()
    {

    }

    /**
     * Конфигурирует Sidebar компонента.
     */
    protected function configureSideBar()
    {

    }
}
 