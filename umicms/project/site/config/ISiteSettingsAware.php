<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\config;

use umi\config\entity\IConfig;

/**
 * Интерфейс для использования настроек сайта.
 */
interface ISiteSettingsAware
{
    /**
     * Устанавливает настройки сайта.
     * @param IConfig $config
     */
    public function setSiteSettings(IConfig $config);
}
