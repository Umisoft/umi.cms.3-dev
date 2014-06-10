<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\admin\rss\controller;

use umicms\project\admin\rest\controller\BaseAdminComponentLayoutController;
use umicms\project\admin\layout\AdminComponentLayout;
use umicms\project\admin\layout\CollectionComponentLayout;

/**
 * Контроллер сетки интерфейса административного компонента.
 */
class LayoutController extends BaseAdminComponentLayoutController
{

    /**
     * Возвращет сетку интерфейса компонента.
     * @return AdminComponentLayout
     */
    protected function getLayout()
    {
        $layout = new CollectionComponentLayout($this->getComponent());

        $editForm = $layout->getSelectedContextControl('editForm');
        $editForm->addToolbarButton(
            'execute',
            $editForm->createActionButton('execute', ['action' => 'importFromRss'])
        );

        return $layout;
    }
}
 