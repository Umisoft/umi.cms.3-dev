<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\layout;

use umicms\hmvc\url\IUrlManager;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\layout\control\ComponentsMenuControl;

/**
 * Билдер сетки для компонента, группирующего компоненты настроек.
 */
class SettingsGroupComponentLayout extends AdminComponentLayout
{
    /**
     * @var IUrlManager $urlManager URL-менеджер
     */
    protected $urlManager;

    /**
     * {@inheritdoc}
     */
    public function __construct(AdminComponent $component, IUrlManager $urlManager)
    {
        $this->urlManager = $urlManager;
        parent::__construct($component);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureSideBar()
    {
        $menu = new ComponentsMenuControl($this->component, $this->urlManager);
        $this->addSideBarControl('menu', $menu);
    }
}
 