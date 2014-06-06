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
use umicms\project\admin\layout\button\SplitButton;

/**
 * Административный контрол для управления коллекцией.
 */
class CollectionControl extends AdminControl
{
    /**
     * @var CollectionApiComponent $component
     */
    protected $component;
    /**
     * @var ICmsCollection $collection
     */
    protected $collection;


    /**
     * Конструктор.
     * @param CollectionApiComponent $component.
     */
    public function __construct(CollectionApiComponent $component)
    {
        $this->collection = $component->getCollection();
        parent::__construct($component);
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

        $dropdownButton = new SplitButton('', $choices);

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
 