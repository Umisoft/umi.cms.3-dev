<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\survey\model;

use umicms\module\BaseModule;
use umicms\project\module\survey\model\collection\SurveyCollection;
use umicms\project\module\survey\model\collection\AnswerCollection;

/**
 * Модуль "Опросы".
 */
class SurveyModule extends BaseModule
{

    /**
     * Возвращает коллекцию опросов.
     * @return SurveyCollection
     */
    public function survey()
    {
        return $this->getCollection('survey');
    }

    /**
     * Возвращает коллекцию ответов.
     * @return AnswerCollection
     */
    public function answer()
    {
        return $this->getCollection('answer');
    }

}
