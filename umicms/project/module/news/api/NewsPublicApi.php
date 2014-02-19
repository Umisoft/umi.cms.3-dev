<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api;

use umicms\api\BaseComplexApi;
use umicms\api\IPublicApi;

/**
 * Публичное API модуля "Новости"
 */
class NewsPublicApi extends BaseComplexApi implements IPublicApi
{
    /**
     * Возвращает API для работы с новостями.
     * @return NewsItemApi
     */
    public function news()
    {
        return $this->getApi('umicms\project\module\news\api\NewsItemApi');
    }

    /**
     * Возвращает API для работы с новостными рубриками.
     * @return NewsRubricApi
     */
    public function rubric()
    {
        return $this->getApi('umicms\project\module\news\api\NewsRubricApi');
    }

    /**
     * Возвращает API для работы с новостными сюжетами.
     * @return NewsSubjectApi
     */
    public function subject()
    {
        return $this->getApi('umicms\project\module\news\api\NewsSubjectApi');
    }
}
