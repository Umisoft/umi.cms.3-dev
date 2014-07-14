<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\site;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\http\Response;
use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\widget\BaseFormWidget;

/**
 * Трейт для контроллера, формирующего и обрабатывающего формы
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
     * @see BaseController::isRequestMethodPost()
     */
    abstract protected function isRequestMethodPost();

    /**
     * @see BaseController::getAllPostVars()
     */
    abstract protected function getAllPostVars();

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
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * <ul>
     * <li> FormEntityView $form представление формы </li>
     * <li> array $errors список ошибок, возникших при обработке данных формы (не ошибки валидации). Не передается, если ошибок не было </li>
     * </ul>
     *
     * @return Response
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

        $response = $this->buildResponse();
        if (!$formValid) {
            $response->getStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * Возвращает имя шаблона отбражения
     * @return string
     */
    protected function getTemplateName()
    {
        return 'form';
    }

    /**
     * Возвращает переменные для шаблонизации
     * @return array
     */
    protected function buildResponseContent()
    {
        return [];
    }

    /**
     * Формирует ответ
     * @return Response
     */
    protected function buildResponse()
    {
        $result = (array) $this->buildResponseContent();
        $result['form'] = $this->form->getView();

        if (count($this->errors)) {
            $result['errors'] = $this->errors;
        }

        $response = $this->createViewResponse(
            $this->getTemplateName(),
            $result
        );

        return $response;
    }

    /**
     * Формирует ответ с переадресацией.
     * @return Response
     */
    protected function buildRedirectResponse()
    {
        $redirectUrl = null;
        if ($this->form->has(BaseFormWidget::INPUT_REDIRECT_URL)) {
            /**
             * @var IFormElement $redirectUrlInput
             */
            $redirectUrlInput = $this->form->get(BaseFormWidget::INPUT_REDIRECT_URL);
            $redirectUrl = $redirectUrlInput->getValue();
        }

        switch (true) {
            case !$redirectUrl:
            case ($redirectUrl === BaseFormWidget::NO_REDIRECT): {
                return $this->buildResponse();
            }
            case (
                strpos($redirectUrl, '/') === 0 ||
                strpos($redirectUrl, $this->getUrlManager()->getProjectUrl(true)
                ) === 0): {
                return $this->createRedirectResponse($redirectUrl);
            }
            default: {
                return $this->createRedirectResponse($this->getDefaultRedirectUrl());
            }
        }
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
 