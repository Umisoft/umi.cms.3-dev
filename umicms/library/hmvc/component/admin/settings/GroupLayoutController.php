<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin\settings;

use umicms\hmvc\component\admin\BaseLayoutController;
use umicms\hmvc\component\admin\layout\SettingsGroupComponentLayout;

/**
 * Контроллер вывода настроек компонента, группирующего компоненты настроек
 */
class GroupLayoutController extends BaseLayoutController
{
    /**
     * {@inheritdoc}
     */
    protected function getLayout()
    {
        return new SettingsGroupComponentLayout($this->getComponent(), $this->getUrlManager());
    }
}
 