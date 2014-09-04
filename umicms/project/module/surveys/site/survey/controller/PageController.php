<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\site\survey\controller;

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\hmvc\component\site\SitePageController;
use umicms\hmvc\component\site\TFormController;
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
     * @var bool $voted флаг, указывающий на то, голосовал ли текущий пользователь или нет
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

        if (!$this->isAllowed($this->survey)) {
            throw new ResourceAccessForbiddenException(
                $this->survey,
                $this->translate('Access denied')
            );
        }

        return $this->module->survey()->getForm(
            Survey::FORM_VOTE,
            $this->survey->getTypeName(),
            $this->survey
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();

        if ($this->module->checkIfVoted($this->survey)) {
            $this->voted = false;
        } else {
            $this->voted = true;
        }
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
            'voted' => $this->voted,
            'page' => $this->survey
        ];
    }
}
