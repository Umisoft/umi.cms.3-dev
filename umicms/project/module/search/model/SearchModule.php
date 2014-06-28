<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\search\model;

use umicms\module\BaseModule;

/**
 * Модуль "Поиск"
*/
class SearchModule extends BaseModule
{
    //TODO

    /**
     * @return SearchIndexApi
     */
    public function getSearchApi()
    {
        return $this->getApi('umicms\project\module\search\model\SearchIndexApi');
    }
}
 