<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\statistics\admin\metrika\controller;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\hmvc\component\admin\layout\AdminComponentLayout;
use umicms\hmvc\component\admin\layout\control\AdminControl;

/**
 * Контроллер вывода настроек компонента
 */
class LayoutController extends BaseLayoutController
{
    /**
     * {@inheritdoc}
     */
    protected function getLayout()
    {
        $layout = new AdminComponentLayout($this->getComponent());

        $emptyContextControl = new AdminControl($this->getComponent());
        $selectedContextControl = new AdminControl($this->getComponent());

        $layout->addEmptyContextControl('counters', $emptyContextControl);
        $layout->addSelectedContextControl('counter', $selectedContextControl);

        return $layout;
    }
}
