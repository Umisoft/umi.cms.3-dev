<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\admin\rss\controller;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\hmvc\component\admin\layout\CollectionComponentLayout;

/**
 * Контроллер вывода настроек компонента
 */
class LayoutController extends BaseLayoutController
{
    /**
     * Возвращет сетку интерфейса компонента.
     * @return CollectionComponentLayout
     */
    protected function getLayout()
    {
        $layout = new CollectionComponentLayout($this->getComponent());

        $editForm = $layout->getSelectedContextControl('editForm');
        $editForm->addToolbarButton('importFromRss', $editForm->createActionButton('importFromRss'));

        return $layout;
    }
}
