<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\admin;

use HttpException;
use umi\acl\IAclManager;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Request;
use umi\http\Response;

/**
 * Трейт контроллера, поддерживающего некоторые деййствия
 */
trait TActionController
{
    /**
     * @var int $responseStatusCode код статуса ответа
     */
    protected $responseStatusCode = Response::HTTP_OK;

    /**
     * @see BaseController::getRouteVar()
     */
    abstract protected function getRouteVar($name, $default = null);
     /**
     * @see BaseController::getRequest()
     * @return Request
     */
    abstract protected function getRequest();
    /**
     * @see BaseController::getComponent()
     * @return AdminComponent
     */
    abstract protected function getComponent();
    /**
     * @see BaseController::isAllowed()
     */
    abstract protected function isAllowed($resource, $operationName = IAclManager::OPERATION_ALL);
    /**
     * Создает результат работы контроллера.
     * @param string $content содержимое ответа
     * @param int $code код ответа
     * @return Response
     */
    abstract protected function createResponse($content, $code = Response::HTTP_OK);
    /**
     * Создает шаблонизируемый результат работы контроллера.
     * Этот ответ пройдет через View слой компонента.
     * @param string $templateName имя шаблона
     * @param array $variables переменные
     * @return Response
     */
    abstract protected function createViewResponse($templateName, array $variables = []);
    /**
     * @see BaseController::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $actionName = $this->getRouteVar('action');

        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $this->checkSupportedAction($actionName, $this->getComponent()->getQueryActions());
                return $this->callAction($actionName);
            }
            case 'PUT': {

            }
            case 'POST': {
                $this->checkSupportedAction($actionName, $this->getComponent()->getModifyActions());
                return $this->callAction($actionName);
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    $this->translate('HTTP method is not allowed.'),
                    ['GET', 'PUT', 'POST']
                );
            }
            default: {
                throw new HttpException(
                    Response::HTTP_NOT_IMPLEMENTED,
                    $this->translate('HTTP method is not implemented.')
                );
            }
        }
    }

    /**
     * Вызывает действие.
     * @param string $actionName имя действия
     * @throws HttpForbidden если у текущего пользователя нет доступа к экшену.
     * @return Response
     */
    protected function callAction($actionName)
    {
        if (!$this->isAllowed($this, $actionName)) {
            throw new HttpForbidden(
                $this->translate(
                    'Cannot execute action "{action}" for component "{path}". Access denied.',
                    [
                        'action' => $actionName,
                        'path' => $this->getComponent()->getPath()
                    ]
                )
            );
        }

        $methodName = 'action' . ucfirst($actionName);
        $actionResult = $this->{$methodName}();

        if ($actionResult === '') {
            return $this->createResponse('', Response::HTTP_NO_CONTENT);
        }

        if ($actionResult instanceof Response) {
            return $actionResult;
        }

        $response = $this->createViewResponse(
            $actionName,
            [$actionName => $actionResult]
        );
        $response->setStatusCode($this->getResponseStatusCode());

        return $response;
    }

    /**
     * Возвращает статус ответа.
     * @return int
     */
    protected function getResponseStatusCode()
    {
        return $this->responseStatusCode;
    }

    /**
     * Устанавливает код статуса ответа.
     * @param int $code
     * @return $this
     */
    protected function setResponseStatusCode($code)
    {
        $this->responseStatusCode = $code;

        return $this;
    }

    /**
     * Проверяет, поддерживается ли действие над объектом.
     * @param string $actionName имя действия
     * @param array $supportedActions список поддерживаемых действий
     * @throws HttpNotFound если действие не поддерживается
     */
    protected function checkSupportedAction($actionName, array $supportedActions)
    {
        if (!isset($supportedActions[$actionName])) {
            throw new HttpNotFound($this->translate('Action is not supported.'));
        }
    }
}
 