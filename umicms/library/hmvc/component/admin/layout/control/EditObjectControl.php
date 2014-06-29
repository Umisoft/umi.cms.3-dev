<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\layout\control;

use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\IRobotsAccessibleCollection;
use umicms\orm\collection\ICmsPageCollection;
use umicms\hmvc\component\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\hmvc\component\admin\layout\button\SplitButton;

/**
 * Административный контрол "Форма редактирования объекта коллекции"
 */
class EditObjectControl extends CollectionControl
{
    /**
     * {@inheritdoc}
     */
    protected function configureToolbar()
    {
        if ($createButton = $this->buildCreateButton()) {
            $this->addToolbarButton('create', $createButton);
        }

        $this->addToolbarButton('backToFilter', $this->createActionButton('backToFilter'));

        if ($this->collection instanceof IActiveAccessibleCollection) {
            $this->addToolbarButton('switchActivity', $this->createSwitchActivityButton());
        }

        if ($this->collection instanceof IRobotsAccessibleCollection) {
            $this->addToolbarButton('switchRobots', $this->createRobotsAccessibleButton());
        }

        if ($this->collection instanceof ICmsPageCollection) {
            $this->addToolbarButton('viewOnSite', $this->createActionButton('viewOnSite'));
        }

        if ($this->collection instanceof IRecyclableCollection) {
            $this->addToolbarButton('trash', $this->createActionButton('trash'));
        } else {
            $this->addToolbarButton('delete', $this->createActionButton('delete'));
        }

        if ($this->collection instanceof IRecoverableCollection && $this->collection->isBackupEnabled()) {
            $this->addToolbarButton('backupList', $this->createActionDropdownButton('backupList'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSubmitToolbar()
    {

        $behaviour = new ChoicesBehaviour('save');
        $behaviour->addChoice('save', $this->createActionChoice('save'));
        $behaviour->addChoice('saveAndGoBack', $this->createActionChoice('saveAndGoBack'));

        $saveButton = new SplitButton($this->component->translate('button:save'), $behaviour);
        $saveButton->attributes['hasIcon'] = false;

        $saveButton
            ->addState('modified', [
                'label' => $this->component->translate('button:save')
            ])
            ->addState('notModified', [
                'label' => $this->component->translate('button:save:notModified')
            ]);

        $this->addSubmitButton('save', $saveButton);
    }

}
 