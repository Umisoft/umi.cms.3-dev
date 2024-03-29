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

use umicms\hmvc\component\admin\collection\CollectionComponent;

/**
 * Административный контрол "Таблица для управления коллекцией"
 */
class TableControl extends CollectionControl
{
    /**
     * {@inheritdoc}
     */
    protected function configureParams()
    {
        $this->params['action'] = CollectionComponent::ACTION_GET_FILTER;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureToolbar()
    {
        if ($createButton = $this->buildCreateButton()) {
            $this->addToolbarButton('create', $createButton);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureI18n()
    {
        $this->labels['Rows on page'] = $this->component->translate('Rows on page');
        $this->labels['No data'] = $this->component->translate('No data');
        $this->labels['Selected fields'] = $this->component->translate('Selected fields');
        $this->labels['Apply'] = $this->component->translate('Apply');
        $this->labels['Default'] = $this->component->translate('Default');
    }
}
 