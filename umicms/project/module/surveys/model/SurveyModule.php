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

use Symfony\Component\HttpFoundation\Cookie;
use umi\http\Request;
use umi\http\Response;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\surveys\model\collection\SurveyCollection;
use umicms\project\module\surveys\model\collection\AnswerCollection;
use umicms\project\module\surveys\model\object\Answer;
use umicms\project\module\surveys\model\object\Survey;

/**
 * Модуль "Опросы".
 */
class SurveyModule extends BaseModule
{
    /**
     * @var Request $request текущий запрос
     */
    protected $request;
    /**
     * @var Survey[] $voted список опросов, в которых текущий пользователь принял участие
     */
    private $voted = [];

    /**
     * Конструктор.
     * @param Request $request текущий запрос
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
     * @param Survey $survey
     * @return CmsSelector|Answer[]
     */
    public function getAnswersBySurvey(Survey $survey)
    {
        return
            $this->getAnswers()
                ->where(Answer::FIELD_SURVEY)->equals($survey);
    }

    /**
     * Проверяет, голосовал ли текущий пользователь
     * @param Survey $survey
     * @return bool
     */
    public function checkIfVoted(Survey $survey)
    {
        return $this->request->cookies->has($survey->guid) || array_key_exists($survey->guid, $this->voted);
    }

    /**
     * Помечает текущего пользователя как проголововавшего
     * @param Survey $survey
     * @param Response $response
     */
    public function markAsVoted(Survey $survey, Response $response)
    {
        $this->voted[$survey->guid] = $survey;

        $cookie = new Cookie(
            $survey->guid,
            true,
            new \DateTime('+5 year')
        );
        $response->headers->setCookie($cookie);
    }
}
