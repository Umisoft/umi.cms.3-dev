<?php

namespace umicms\project\module\surveys\site\survey\vote\controller;

use umi\form\IForm;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\orm\metadata\IObjectType;
use umicms\hmvc\component\site\SitePageController;
use umicms\hmvc\component\site\TFormController;
use umicms\project\module\surveys\model\object\Survey;
use umicms\project\module\surveys\model\SurveyModule;

/**
 * Контроллер для отображения опроса.
 */
class VoteController extends SitePageController implements IHttpAware
{
    use TFormController;
    use THttpAware;

    /**
     * @var string $template имя шаблона, по которому выводится результат
     */
    public $template = 'voteSurvey';
    /**
     * @var SurveyModule $module модуль "Опросы"
     */
    protected $module;
    /**
     * @var Survey $survey объект опроса
     */
    protected $survey;
    /**
     * @var bool $success флаг, указывающий на успешное сохранение изменений
     */
    private $success = false;

    /**
     * Конструктор.
     * @param SurveyModule $module модуль "Блоги"
     */
    public function __construct(SurveyModule $module)
    {
        $this->module = $module;
        $this->survey = $this->module->survey()->getById($this->getRouteVar('id'));
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
        $survey = $this->module->survey()->getById($this->getRouteVar('id'));

        if (!$this->isAllowed($survey)) {
            throw new ResourceAccessForbiddenException(
                $survey,
                $this->translate('Access denied')
            );
        }

        return $this->module->survey()->getForm(
            Survey::FORM_VOTE,
            IObjectType::BASE,
            $survey
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form)
    {
        $this->commit();

        if ($this->module->checkIfVoted($this->survey)) {
            $this->success = false;
        } else {
            $this->success = true;
        }
    }

    /**
     * Дополняет результат параметрами для шаблонизации.
     *
     * @templateParam bool $success флаг, указывающий на успешное голосование
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница опроса
     *
     * @return array
     */
    protected function buildResponseContent()
    {
        $this->module->markAsVoted($this->survey);

        return [
            'success' => $this->success,
            'page' => $this->getCurrentPage()
        ];
    }
}
