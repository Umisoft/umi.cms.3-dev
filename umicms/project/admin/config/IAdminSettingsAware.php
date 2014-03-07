<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\config;

use umi\config\entity\IConfig;

/**
 * Интерфейс для использования настроек административной панели.
 */
interface IAdminSettingsAware
{
    /**
     * Устанавливает настройки административной панели.
     * @param IConfig $config
     */
    public function setAdminSettings(IConfig $config);
}
