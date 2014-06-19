<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context\admin;

use tests\context\admin\dictionary\TAuthenticationDictionary;
use tests\context\admin\dictionary\TComponentDictionary;
use tests\context\BaseCmsContext;

/**
 * Контекст аутентификации администратора
 */
class AdminPanelContext extends BaseCmsContext
{
    use TAuthenticationDictionary;
    use TComponentDictionary;

}
 