<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\model;

use umi\http\IHttpAware;
use umi\http\THttpAware;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\surveys\model\collection\SurveyCollection;
use umicms\project\module\surveys\model\collection\AnswerCollection;
use umicms\project\module\surveys\model\object\Answer;
use umicms\project\module\surveys\model\object\Survey;

/**
 * Модуль "Опросы".
 */
class SurveyModule extends BaseModule implements IHttpAware
{
    use THttpAware;

    protected $statisticModule;

    public function __construct($statistic)
    {
        $this->statisticModule = $statistic;
    }

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

    /**
     * Возвращает селектор для выборки опросов.
     * @return CmsSelector|Survey[]
     */
    public function getSurveys()
    {
        return $this->survey()->select();
    }

    /**
     * Возвращает селектор для выборки ответов.
     * @return CmsSelector|Answer[]
     */
    public function getAnswers()
    {
        return $this->answer()->select();
    }

    /**
     * Возвращает селектор для выбора ответов опроса.
     * @param object\Survey $survey
     * @return CmsSelector|Answer[]
     */
    public function getAnswersBySurvey(Survey $survey) {
        $answers = $this->getAnswers();

        $answers->begin();
        $answers->where(Answer::FIELD_SURVEY)->equals($survey);
        $answers->end();

        return $answers;
    }

    /**
     * Возвращет, голосовал ли текущий пользователь
     * @param Survey $survey
     * @return bool
     */
    public function checkIfVoted(Survey $survey)
    {
        return $this->getHttpRequest()->cookies->has($survey->guid);
    }

    /**
     * Помечает текущего пользователя как проголововавшего
     * @param Survey $survey
     */
    public function markAsVoted(Survey $survey)
    {
        $this->getHttpRequest()->cookies->set($survey->guid, true);
        //TODO: mark with statistic module. Now it's unavailable.
    }
}
