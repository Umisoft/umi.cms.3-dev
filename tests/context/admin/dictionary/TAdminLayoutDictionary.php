<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context\admin\dictionary;

use tests\context\admin\page\AdminComponentPage;
use tests\context\BaseCmsContext;

/**
 * Словарь для работы с административными страницами
 * @mixin BaseCmsContext
 */
trait TAdminLayoutDictionary
{
    /**
     * Должен вернуть конкретную административную страницу
     * @return AdminComponentPage
     */
    abstract protected function getAdminComponentPage();


}
