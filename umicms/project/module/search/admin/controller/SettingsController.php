<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\admin\controller;

use umicms\project\admin\api\controller\DefaultRestSettingsController;

/**
 * Контроллер вывода настроек компонента
 */
class SettingsController extends DefaultRestSettingsController
{
    /**
     * {@inheritdoc}
     */
    protected function getSettings()
    {
        return [ ];
    }
}
