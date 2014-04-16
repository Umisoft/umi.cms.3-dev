<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
