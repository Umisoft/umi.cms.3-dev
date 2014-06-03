<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\controller;

use umi\form\element\IFormElement;
use umi\form\IForm;
use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umi\i18n\TLocalizable;
use umicms\hmvc\url\IUrlManager;
use umicms\hmvc\widget\BaseFormWidget;

/**
 * Базовый контроллер для обработки форм не имеющих страницы.
 */
trait TFormSimpleController
{
    use TLocalizable;
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
        if (!$this->isRequestMethodPost()) {
            throw new HttpNotFound($this->translate(
                'Page not found.'
            ));
        }

        $this->form = $this->buildForm();
        $this->form->setData($this->getAllPostVars());

        if (!$this->form->isValid()) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Form is invalid.')
            );
        }

        $this->processForm($this->form);

        $response = $this->buildResponse();
        return $response;
    }

    /**
     * Формирует ответ
     * @return Response
     */
    protected function buildResponse()
    {
        return $this->buildRedirectResponse();
    }

    /**
     * Формирует ответ с переадресацией.
     * @throws HttpException
     * @return Response
     */
    protected function buildRedirectResponse()
    {
        $redirectUrl = null;
        if ($this->form->has(BaseFormWidget::INPUT_REDIRECT_URL)) {
            /** @var IFormElement $redirectUrlInput */
            $redirectUrlInput = $this->form->get(BaseFormWidget::INPUT_REDIRECT_URL);
            $redirectUrl = $redirectUrlInput->getValue();
        }

        switch (true) {
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
                throw new HttpException(
                    Response::HTTP_SEE_OTHER,
                    $this->translate('Cannot detect redirect url.')
                );
            }
        }
    }
}
 