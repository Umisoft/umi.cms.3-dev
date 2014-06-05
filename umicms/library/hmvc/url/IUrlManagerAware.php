<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\url;

/**
 * Интерфейс для доступа к URL-менеджеру.
 */
interface IUrlManagerAware
{
    /**
     * Устанавливает URL-менеджер
     * @param IUrlManager $urlManager
     */
    public function setUrlManager(IUrlManager $urlManager);
}
 