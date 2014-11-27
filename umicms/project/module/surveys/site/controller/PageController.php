<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\site\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\orm\object\property\calculable\ICounterProperty;
use umicms\exception\NotAllowedOperationException;
use umicms\hmvc\component\site\SitePageController;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\surveys\model\object\Answer;
use umicms\project\module\surveys\model\object\Survey;
use umicms\project\module\surveys\model\SurveyModule;

/**
 * Контроллер для отображения опроса.
 */
class PageController extends SitePageController
{
    use TFormController;

    /**
     * @var SurveyModule $module модуль "Опросы"
     */
    protected $module;
    /**
     * @var Survey $survey опрос
     */
    protected $survey;
    /**
     * @var bool $voted флаг принятия текущего пользователя участия в опросе
     */
    private $voted = false;

    /**
     * Конструктор.
     * @param SurveyModule $module модуль "Опросы"
     */
    public function __construct(SurveyModule $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTemplateName()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildForm()
    {
        $uri = $this->getRouteVar('uri');
        $this->survey = $this->getPage($uri);
        $this->pushCurrentPage($this->survey);

        return $this->module->survey()->getVoteForm($this->survey);
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        if ($this->module->checkIfVoted($this->survey)) {
            throw new NotAllowedOperationException(
                $this->translate('You have already voted')
            );
        }

        /**
         * @var IFormElement $answersElement
         */
        $answersElement = $form->get(Survey::FIELD_ANSWERS);
        $answers = (array) $answersElement->getValue();

        foreach ($answers as $guid) {
            $answer = $this->module->answer()->get($guid);
            /**
             * @var ICounterProperty $votesProperty
             */
            $votesProperty = $answer->getProperty(Answer::FIELD_VOTES);
            $votesProperty->increment();
        }

        $this->voted = true;
        $this->commit();

        return $this->buildRedirectResponse();
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $voted флаг, указывающий на то, голосовал ли текущий пользователь или нет
     * @templateParam umicms\project\module\surveys\model\object\Survey $page текущая страница опроса
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        return [
            'voted' => $this->voted || $this->module->checkIfVoted($this->survey),
            'page' => $this->survey
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function createHttpResponse()
    {
        $response = parent::createHttpResponse();

        if ($this->voted) {
            $this->module->markAsVoted($this->survey, $response);
        }

        return $response;
    }
}
