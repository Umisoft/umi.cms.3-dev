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

use umicms\project\admin\layout\button\behaviour\ChoicesBehaviour;
use umicms\project\admin\layout\button\SplitButton;

/**
 * Административный контрол "Форма создания объекта коллекции"
 */
class CreateObjectControl extends CollectionControl
{
    /**
     * Конфигурирует Submit-кнопки.
     */
    protected function configureSubmitToolbar()
    {
        $behaviour = new ChoicesBehaviour('add');
        $behaviour->addChoice('add', $this->createActionChoice('add'));
        $behaviour->addChoice('addAndGoBack', $this->createActionChoice('addAndGoBack'));
        $behaviour->addChoice('addAndCreate', $this->createActionChoice('addAndCreate'));

        $saveButton = new SplitButton($this->component->translate('button:add'), $behaviour);
        $this->addSubmitButton('add', $saveButton);

    }
}
 