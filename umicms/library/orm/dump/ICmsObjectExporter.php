<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\dump;

use umicms\orm\selector\CmsSelector;

/**
 * Интерфейс для экспорта объектов в дамп.
 */
interface ICmsObjectExporter
{
    /**
     * Возвращает дамп объектов, выбранных селектором.
     * @param CmsSelector $selector
     * @return array
     */
    public function getDump(CmsSelector $selector);
}
