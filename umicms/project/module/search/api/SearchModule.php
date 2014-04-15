<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\search\api;

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
        return $this->getApi('umicms\project\module\search\api\SearchIndexApi');
    }
}
 