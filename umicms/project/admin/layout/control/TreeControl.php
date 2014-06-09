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

/**
 * Административный контрол "Дерево для упарвления иерархической коллекцией"
 */
class TreeControl extends CollectionControl
{
    /**
     * {@inheritdoc}
     */
    protected function configureParams()
    {
        $this->params['rootNodeName'] = $this->component->translate(
            'component:' . $this->component->getName() . ':displayName'
        );
    }

}
 