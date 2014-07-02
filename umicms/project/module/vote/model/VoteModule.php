<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\vote\model;

use umicms\module\BaseModule;
use umicms\project\module\vote\model\collection\VoteCollection;
use umicms\project\module\vote\model\collection\AnswerCollection;

/**
 * Модуль "Опросы".
 */
class VoteModule extends BaseModule
{

    /**
     * Возвращает коллекцию опросов.
     * @return VoteCollection
     */
    public function vote()
    {
        return $this->getCollection('vote');
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
