<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\site\survey\widget;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\surveys\model\object\Survey;
use umicms\project\module\surveys\model\SurveyModule;

/**
 * Виджет для вывода опроса.
 */
class VoteFormWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'voteForm';
    /**
     * {@inheritdoc}
     */
    public $redirectUrl = self::REFERER_REDIRECT;
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
     * {@inheritdoc}
     */
    protected function getForm()
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

        if (!$this->isAllowed($this->survey)) {
            throw new ResourceAccessForbiddenException(
                $this->survey,
                $this->translate('Access denied')
            );
        }

        $form = $this->module->survey()->getForm(
            Survey::FORM_VOTE,
            $this->survey->getTypeName(),
            $this->survey
        );

        $form->setAction($this->getUrl('page', ['uri' => $this->survey->slug]));

        return $form;
    }
}
 