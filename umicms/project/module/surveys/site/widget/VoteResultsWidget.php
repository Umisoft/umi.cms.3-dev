<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\site\widget;

use umicms\exception\InvalidArgumentException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\surveys\model\object\Survey;
use umicms\project\module\surveys\model\SurveyModule;

/**
 * Виджет вывода результатов опроса
 */
class VoteResultsWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'results';
    /**
     * @var string|Survey $survey опрос или GUID опроса
     */
    public $survey;
    /**
     * @var SurveyModule $module модуль "Опросы"
     */
    protected $module;

    /**
     * Конструктор.
     * @param SurveyModule $module модуль "Опросы"
     */
    public function __construct(SurveyModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы виджета.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\surveys\model\object\Survey $survey текущий опрос
     * @templateParam int $total общее количество проголосовавших
     * @templateParam array $answers список вариантов ответов в формате
     * [
     *     'answer' => umicms\project\module\surveys\model\object\Answer,
     *     'percentage' => float,
     *     'votes' => int
     * ]
     *
     * @throws InvalidArgumentException если не удалось определить опрос
     * @return CmsView
     */
    public function __invoke()
    {
        if (is_string($this->survey)) {
            $this->survey = $this->module->survey()->get($this->survey);
        }

        if (!$this->survey instanceof Survey) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Widget parameter "{param}" should be instance of "{class}".',
                    [
                        'param' => 'survey',
                        'class' => Survey::className()
                    ]
                )
            );
        }

        $total = 0;
        foreach ($this->survey->answers as $answer) {
            $total += $answer->votes;
        }

        $answers = [];
        foreach ($this->survey->answers as $answer) {
            $answers[$answer->guid] = [
                'answer' => $answer,
                'percentage' => $total ? round($answer->votes/$total * 100, 2) : 0,
                'votes' => $answer->votes
            ];
        }

        return $this->createResult(
            $this->template,
            [
                'survey' => $this->survey,
                'total' => $total,
                'answers' => $answers
            ]
        );
    }
}
 