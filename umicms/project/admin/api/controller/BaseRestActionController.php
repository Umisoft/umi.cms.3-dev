<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\config\entity\IConfig;
use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umicms\project\admin\component\AdminComponent;

/**
 * Базовый контроллер действий над объектом.
 */
abstract class BaseRestActionController extends BaseRestController
{
    /**
     * Возвращает список доступных действий на запрос данных.
     * @return array
     */
    abstract protected function getQueryActions();

    /**
     * Возвращает список доступных действий на изменение данных.
     * @return array
     */
    abstract protected function getModifyActions();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $action = $this->getRouteVar('action');

        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $this->checkSupportedAction($action, $this->getQueryActions());
                return $this->callAction($action);
            }
            case 'PUT': {

            }
            case 'POST': {
                $this->checkSupportedAction($action, $this->getModifyActions());
                return $this->callAction($action);
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    'HTTP method is not implemented.',
                    ['GET', 'PUT', 'DELETE']
                );
            }
            default: {
                throw new HttpException(
                    Response::HTTP_NOT_IMPLEMENTED,
                    'HTTP method is not implemented.'
                );
            }
        }
    }

    /**
     * Возвращает настройки текущего компонента.
     * @return IConfig
     */
    protected function actionSettings()
    {
        /**
         * @var AdminComponent $component
         */
        $component = $this->getComponent();

        return $component->getSettings();
    }

    /**
     * Вызывает действие.
     * @param string $action имя действия
     * @return Response
     */
    protected function callAction($action)
    {
        $methodName = 'action' . ucfirst($action);
        $actionResult = $this->{$methodName}();

        if (!$actionResult) {
            return $this->createResponse('', Response::HTTP_NO_CONTENT);
        } else {
            return $this->createViewResponse(
                $action,
                [$action => $actionResult]
            );
        }
    }

    /**
     * Проверяет, поддерживается ли действие над объектом.
     * @param string $action имя действия
     * @param array $supportedActions список поддерживаемых действий
     * @throws HttpNotFound если действие не поддерживается
     */
    private function checkSupportedAction($action, array $supportedActions)
    {
        if (!in_array($action, $supportedActions)) {
            throw new HttpNotFound('Action not found.');
        }
    }

}
 