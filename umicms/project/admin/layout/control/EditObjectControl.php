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
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\project\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\project\admin\layout\button\SplitButton;

/**
 * Административный контрол "Форма редактирования объекта коллекции"
 */
class EditObjectControl extends CollectionControl
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->configureToolbar();
        $this->configureSubmitToolbar();
    }

    /**
     * Конфигурирует toolbar.
     * @return $this
     */
    protected function configureToolbar()
    {

        $this->addToolbarButton('backToFilter', $this->createActionButton('backToFilter'));


        if ($this->collection instanceof IActiveAccessibleCollection) {
            $this->addToolbarButton('switchActivity', $this->createActionButton('switchActivity'));
        }

        if ($this->collection instanceof IRecyclableCollection) {
            $this->addToolbarButton('trash', $this->createActionButton('trash'));
        } else {
            $this->addToolbarButton('delete', $this->createActionButton('delete'));
        }

        if ($this->collection instanceof IRecoverableCollection && $this->collection->isBackupEnabled()) {
            $this->addToolbarButton('backupList', $this->createActionDropdownButton('backupList'));
        }

        return $this;
    }

    /**
     * Конфигурирует Submit-кнопки.
     * @return $this
     */
    protected function configureSubmitToolbar()
    {

        $behaviour = new ChoicesBehaviour('save');
        $behaviour->addChoice('save', $this->createActionChoice('save'));
        $behaviour->addChoice('saveAndGoBack', $this->createActionChoice('saveAndGoBack'));
        //$behaviour->addChoice('saveAsCopy', $this->createActionChoice('saveAsCopy'));

        $saveButton = new SplitButton($this->component->translate('button:save'), $behaviour);
        $this->addSubmitButton('save', $saveButton);

        return $this;
    }

}
 