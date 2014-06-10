<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\settings\admin\component;

use umicms\project\admin\component\AdminComponent;

/**
 * Компонент настроек.
 */
class SettingsComponent extends AdminComponent
{
    /**
     * Возвращает информацию о компоненте.
     * @return array
     */
    public function getComponentInfo()
    {
        $info = parent::getComponentInfo();

        if (!$this->hasController('index')) {
            unset($info['resource']);
        }

        return $info;
    }

}
 