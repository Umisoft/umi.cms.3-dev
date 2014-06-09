<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\files\admin\manager\controller;

use umicms\project\admin\api\controller\BaseAdminComponentLayoutController;
use umicms\project\admin\layout\AdminComponentLayout;
use umicms\project\admin\layout\control\AdminControl;

/**
 * Контроллер вывода настроек компонента
 */
class LayoutController extends BaseAdminComponentLayoutController
{

    /**
     * Возвращет сетку интерфейса компонента.
     * @return AdminComponentLayout
     */
    protected function getLayout()
    {
        $layout = new AdminComponentLayout($this->getComponent());

        $fileManager = new AdminControl($this->getComponent());
        $layout->addEmptyContextControl('fileManager', $fileManager);

        return $layout;
    }
}
