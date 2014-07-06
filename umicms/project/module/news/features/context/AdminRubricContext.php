<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\features\context;

use tests\context\admin\dictionary\TAdminLayoutDictionary;
use tests\context\admin\dictionary\TAuthenticationDictionary;
use tests\context\admin\page\AdminComponentPage;
use tests\context\BaseCmsContext;
use umicms\project\module\news\features\context\page\admin\AdminRubricPage;

/**
 * Контекст для тестирования рубрик
 */
class AdminRubricContext extends BaseCmsContext
{
    use TAuthenticationDictionary;
    use TAdminLayoutDictionary;

    /**
     * Должен вернуть конкретную административную страницу
     * @return AdminComponentPage
     */
    protected function getAdminComponentPage()
    {
        return $this->getPage(AdminRubricPage::className());
    }

    /**
     * @Given я нахожусь в компоненте "Новостные рубрики"
     */
    public function iAmInAdminRubricPage()
    {
        $this->getAdminComponentPage()->open();
    }
}
 