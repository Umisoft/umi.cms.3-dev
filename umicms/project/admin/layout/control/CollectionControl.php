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

use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\project\admin\api\component\CollectionApiComponent;
use umicms\project\admin\layout\button\behaviour\Behaviour;
use umicms\project\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\project\admin\layout\button\Button;
use umicms\project\admin\layout\button\Choice;
use umicms\project\admin\layout\button\DropdownButton;

/**
 * Административный контрол для управления коллекцией.
 */
abstract class CollectionControl
{
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
     * @var CollectionApiComponent $component
     */
    protected $component;
    /**
     * @var ICmsCollection $collection
     */
    protected $collection;

    /**
     * Конфигурирует контрол.
     */
    abstract protected function configure();

    /**
     * Конструктор.
     * @param CollectionApiComponent $component.
     */
    public function __construct(CollectionApiComponent $component)
    {
        $this->component = $component;
        $this->collection = $component->getCollection();
        $this->configure();
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
            $contextToolbar[$name] = $button->build();
        }

        $toolbar = [];
        foreach ($this->toolbar as $name => $button) {
            $toolbar[$name] = $button->build();
        }

        $submitToolbar = [];
        foreach ($this->submitToolbar as $name => $button) {
            $submitToolbar[$name] = $button->build();
        }

        $result = [];
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
     * Конфигурирует контекстное меню.
     * @return $this
     */
    protected function configureContextMenu()
    {
        $choices = new ChoicesBehaviour('contextMenu');
        $this->configureCreateChoiceList($choices);

        if ($this->collection instanceof IActiveAccessibleCollection) {
            $choices->addChoice('switchActivity', $this->createActionChoice('switchActivity'));
        }

        if ($this->collection instanceof ICmsPageCollection) {
            $choices->addChoice('viewOnSite', $this->createActionChoice('viewOnSite'));
        }

        $dropdownButton = new DropdownButton('', $choices);

        $this->addContextButton('contextMenu', $dropdownButton);

        return $this;
    }

    /**
     * Возвращает список имен типов, доступных для создания
     * @return array
     */
    protected function getCreateTypeList()
    {
        $typeNames = array_merge(
            [IObjectType::BASE],
            $this->collection->getMetadata()
                ->getDescendantTypesList()
        );

        $result = [];
        foreach ($typeNames as $typeName) {
            if ($this->collection->hasForm(ICmsCollection::FORM_CREATE, $typeName)) {
                $result[] = $typeName;
            }
        }

        return $result;
    }

    /**
     * Конфигурирует опции выбора для создания разнотипных объектов
     * @param ChoicesBehaviour $choices
     */
    protected function configureCreateChoiceList(ChoicesBehaviour $choices)
    {
        foreach ($this->getCreateTypeList() as $typeName) {
            $choices->addChoice($typeName, $this->createTypeChoice($typeName));
        }
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
     * Создает вариант выбора для создания объекта определенного типа.
     * @param string $typeName имя типа
     * @return Choice
     */
    protected function createTypeChoice($typeName)
    {
        $label = $this->component->translate('action:create:' . $typeName);
        return new Choice($label, new Behaviour('create', ['typeName' => $typeName]));
    }
}
 