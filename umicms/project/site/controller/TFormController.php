<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\http\Request;
use umi\http\Response;
use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\widget\BaseFormWidget;

/**
 * Базовый контроллер для обработки форм
 */
trait TFormController
{
    /**
     * @var array $errors ошибки исполнения
     */
    protected $errors = [];
    /**
     * @var IForm $form форма для обработки
     */
    private $form;

    /**
     * Возвращает имя шаблона отбражения
     * @return string
     */
    abstract protected function getTemplateName();

    /**
     * Возвращает форму для обработки
     * @return IForm
     */
    abstract protected function buildForm();

    /**
     * Обрабатывает валидную форму
     * @param IForm $form
     * @return null|Response
     */
    abstract protected function processForm(IForm $form);

    /**
     * Возвращает переменные для шаблонизации
     * @return array
     */
    abstract protected function buildResponseContent();

    /**
     * @see BaseController::isRequestMethodPost()
     */
    abstract protected function isRequestMethodPost();

    /**
     * @see BaseController::getAllPostVars()
     */
    abstract protected function getAllPostVars();

    /**
     * @see BaseController::getRequest()
     * @return Request
     */
    abstract protected function getRequest();

    /**
     * @see BaseController::getUrlManager()
     * @return IUrlManager
     */
    abstract protected function getUrlManager();

    /**
     * @see BaseController::createViewResponse()
     * @param string $templateName
     * @param array $variables
     * @return Response
     */
    abstract protected function createViewResponse($templateName, array $variables = []);

    /**
     * @see BaseController::createRedirectResponse()
     * @param string $url
     * @param int $code
     * @return Response
     */
    abstract protected function createRedirectResponse($url, $code = Response::HTTP_SEE_OTHER);

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {

        $this->form = $this->buildForm();
        $formValid = true;

        if ($this->isRequestMethodPost()) {

            $this->form->setData($this->getAllPostVars());
            $formValid = $this->form->isValid();

            if ($formValid) {

                if ($response = $this->processForm($this->form)) {
                    return $response;
                }

            }
        }

        $result = (array) $this->buildResponseContent();
        $result['form'] = $this->form->getView();

        if (count($this->errors)) {
            $result['errors'] = $this->errors;
        }

        $response = $this->createViewResponse(
            $this->getTemplateName(),
            $result
        );

        if (!$formValid) {
            $response->getStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * Формирует ответ с переадресацией.
     * @return Response
     */
    protected function buildRedirectResponse()
    {
        $projectUrl = $this->getUrlManager()->getProjectUrl(true);

        if ($this->form->has(BaseFormWidget::INPUT_REDIRECT_URL)) {
            /**
             * @var IFormElement $redirectUrlInput
             */
            $redirectUrlInput = $this->form->get(BaseFormWidget::INPUT_REDIRECT_URL);
            $redirectUrl = $redirectUrlInput->getValue();

            if (strpos($redirectUrl, '/') === 0 || strpos($redirectUrl, $projectUrl) === 0) {
                return $this->createRedirectResponse($redirectUrl);
            }
        }

        if ($redirectUrl = $this->getRequest()->getReferer()) {
            if (strpos($redirectUrl, $projectUrl) === 0) {
                return $this->createRedirectResponse($redirectUrl);
            }
        }

        return $this->createRedirectResponse($this->getDefaultRedirectUrl());
    }

    /**
     * Возвращает URL для переадресации по умолчанию.
     * @return string
     */
    protected function getDefaultRedirectUrl()
    {
        return $this->getUrlManager()->getCurrentUrl(true);
    }
}
 