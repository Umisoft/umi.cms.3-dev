<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\object;

use umicms\orm\object\ICmsObject;

/**
 * Интерфейс пункта составного меню.
 */
interface IMenuItem extends ICmsObject
{
    /**
     * Возвращает ссылку элемента меню.
     * @return string
     */
    public function getItemUrl();

    /**
     * Возвращает тип ссылки.
     * @return string
     */
    public function getItemType();

}
 