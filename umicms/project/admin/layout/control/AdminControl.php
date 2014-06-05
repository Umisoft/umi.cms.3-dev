<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout\control;

use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\layout\button\behaviour\Behaviour;
use umicms\project\admin\layout\button\Button;
use umicms\project\admin\layout\button\Choice;
use umicms\project\admin\layout\button\DropdownButton;

/**
 * Простой административный контрол.
 */
class AdminControl
{
    /**
     * @var array $params список параметров контрола
     */
    public $params = [];

    /**
     * @var Button[] $contextToolbar кнопки контекстного меню
     */
    protected $contextToolbar = [];
    /**
     * @var Button[] $submitToolbar кнопки для применения изменений
     */
    protected $submitToolbar = [];
    /**
     * @var Button[] $toolbar основной тулбар
     */
    protected $toolbar = [];
    /**
     * @var AdminComponent $component
     */
    protected $component;

    /**
     * Конструктор.
     * @param AdminComponent $component.
     */
    public function __construct(AdminComponent $component)
    {
        $this->component = $component;

        $this->configureParams();
        $this->configureContextMenu();
        $this->configureToolbar();
        $this->configureSubmitToolbar();
    }

    /**
     * Добавляет кнопку в контекстное меню.
     * @param string $name
     * @param Button $button
     * @return $this
     */
    public function addContextButton($name, Button $button)
    {
        $this->contextToolbar[$name] = $button;

        return $this;
    }

    /**
     * Удаляет кнопку из контекстного меню.
     * @param string $name
     * @return $this
     */
    public function removeContextButton($name)
    {
        unset($this->contextToolbar[$name]);

        return $this;
    }

    /**
     * Добавляет кнопку в toolbar.
     * @param string $name
     * @param Button $button
     * @return $this
     */
    public function addToolbarButton($name, Button $button)
    {
        $this->toolbar[$name] = $button;

        return $this;
    }

    /**
     * Удаляет кнопку из toolbar.
     * @param string $name
     * @return $this
     */
    public function removeToolbarButton($name)
    {
        unset($this->toolbar[$name]);

        return $this;
    }

    /**
     * Добавляет submit-кнопку.
     * @param string $name
     * @param Button $button
     * @return $this
     */
    public function addSubmitButton($name, Button $button)
    {
        $this->submitToolbar[$name] = $button;

        return $this;
    }

    /**
     * Удаляет submit-кнопку.
     * @param string $name
     * @return $this
     */
    public function removeSubmitButton($name)
    {
        unset($this->submitToolbar[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $contextToolbar = [];
        foreach ($this->contextToolbar as $name => $button) {
            $info = $button->build();
            $info['name'] = $name;
            $contextToolbar[] = $info;
        }

        $toolbar = [];
        foreach ($this->toolbar as $name => $button) {
            $info = $button->build();
            $info['name'] = $name;
            $toolbar[] = $info;
        }

        $submitToolbar = [];
        foreach ($this->submitToolbar as $name => $button) {
            $info = $button->build();
            $info['name'] = $name;
            $submitToolbar[] = $info;
        }

        $result = [
            'params' => $this->params
        ];
        if ($toolbar) {
            $result['toolbar'] = $toolbar;
        }

        if ($contextToolbar) {
            $result['contextToolbar'] = $contextToolbar;
        }

        if ($submitToolbar) {
            $result['submitToolbar'] = $submitToolbar;
        }

        return $result;
    }

    /**
     * Создает простую кнопку для вызова действия.
     * @param string $actionName
     * @param array $params параметры обработчика
     * @return Button
     */
    protected function createActionButton($actionName, array $params = [])
    {
        $label = $this->component->translate('action:' . $actionName);
        return new Button($label, new Behaviour($actionName, $params));
    }

    /**
     * Создает выпадающую кнопку для вызова действия.
     * @param string $actionName
     * @param array $params параметры обработчика
     * @return DropdownButton
     */
    protected function createActionDropdownButton($actionName, array $params = [])
    {
        $label = $this->component->translate('action:' . $actionName);
        return new DropdownButton($label, new Behaviour($actionName, $params));
    }

    /**
     * Создает вариант выбора для вызова простого действия.
     * @param string $actionName
     * @param array $params параметры обработчика
     * @return Choice
     */
    protected function createActionChoice($actionName, array $params = [])
    {
        $label = $this->component->translate('action:' . $actionName);
        return new Choice($label, new Behaviour($actionName, $params));
    }

    /**
     * Конфигурирует параметры контрола.
     * @return $this
     */
    protected function configureParams()
    {

    }

    /**
     * Конфигурирует контекстное меню.
     * @return $this
     */
    protected function configureContextMenu()
    {

    }

    /**
     * Конфигурирует toolbar.
     * @return $this
     */
    protected function configureToolbar()
    {

    }

    /**
     * Конфигурирует Submit-кнопки.
     * @return $this
     */
    protected function configureSubmitToolbar()
    {

    }
}
 