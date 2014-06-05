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

use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\project\admin\layout\button\behaviour\Behaviour;
use umicms\project\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\project\admin\layout\button\Button;
use umicms\project\admin\layout\button\SplitButton;

/**
 * Административный контрол "Таблица для упарвления коллекцией"
 */
class TableControl extends CollectionControl
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->configureContextMenu();
        $this->configureToolbar();
    }

    /**
     * Конфигурирует toolbar.
     * @return $this
     */
    protected function configureToolbar()
    {
        if ($createButton = $this->buildCreateButton()) {
            $this->addToolbarButton('create', $createButton);
        }

        return $this;
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

        $dropdownButton = new SplitButton($this->component->translate(''), $choices);

        $this->addContextButton('contextMenu', $dropdownButton);

        return $this;
    }
}
 