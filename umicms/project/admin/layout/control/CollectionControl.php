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
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\project\admin\layout\button\Button;
use umicms\hmvc\component\admin\collection\CollectionComponent;
use umicms\project\admin\layout\button\behaviour\Behaviour;
use umicms\project\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\project\admin\layout\button\Choice;
use umicms\project\admin\layout\button\SplitButton;

/**
 * Административный контрол для управления коллекцией.
 */
class CollectionControl extends AdminControl
{
    /**
     * @var CollectionComponent $component
     */
    protected $component;
    /**
     * @var ICmsCollection $collection
     */
    protected $collection;

    /**
     * Конструктор.
     * @param CollectionComponent $component.
     */
    public function __construct(CollectionComponent $component)
    {
        $this->collection = $component->getCollection();
        parent::__construct($component);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContextMenu()
    {
        $choices = new ChoicesBehaviour('contextMenu');
        $this->configureCreateChoiceList($choices);

        if ($this->collection instanceof IActiveAccessibleCollection) {
            $choices->addChoice('switchActivity', $this->createSwitchActivityButton(true));
        }

        if ($this->collection instanceof ICmsPageCollection) {
            $choices->addChoice('viewOnSite', $this->createActionChoice('viewOnSite'));
        }

        if ($this->collection instanceof IRecyclableCollection) {
            $choices->addChoice('trash', $this->createActionChoice('trash'));
        } else {
            $choices->addChoice('delete', $this->createActionChoice('delete'));
        }

        $dropdownButton = new SplitButton('', $choices);

        $this->addContextButton('contextMenu', $dropdownButton);
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
     * Создает вариант выбора для создания объекта определенного типа.
     * @param string $typeName имя типа
     * @return Choice
     */
    protected function createTypeChoice($typeName)
    {
        $label = $this->component->translate('action:create:' . $typeName);
        return new Choice($label, new Behaviour('create', ['typeName' => $typeName]));
    }

    /**
     * Создает кнопку создания объекта, в зависимости от количества возможных для создания типов.
     * @return Button|null
     */
    protected function buildCreateButton()
    {
        $typeList = $this->getCreateTypeList();
        $typesCount = count($typeList);

        if ($typesCount == 1) {
            $label = $this->component->translate('action:create:' . $typeList[0]);
            $behaviour = new Behaviour('create', ['typeName' => $typeList[0]]);

            return new Button($label, $behaviour);
        }

        if ($typesCount > 0) {
            $choices = new ChoicesBehaviour('create');
            $this->configureCreateChoiceList($choices);

            return new SplitButton(
                $this->component->translate('button:create'),
                $choices
            );
        }

        return null;
    }
}
 