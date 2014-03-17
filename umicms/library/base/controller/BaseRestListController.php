<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\base\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;

/**
 * Базовый контроллер действий над списком.
 */
abstract class BaseRestListController extends BaseController
{
    /**
     * Возвращает список.
     * @return \Traversable
     */
    abstract protected function getList();

    /**
     * Создает и возвращает объект списка.
     * @return mixed
     */
    abstract protected function create();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                return $this->createViewResponse(
                    'list', ['list' => $this->getList()]
                );
            }
            case 'PUT':
            case 'POST': {
                return $this->createViewResponse(
                    'list', ['item' => $this->create()]
                );
            }
            case 'DELETE': {
                throw new HttpMethodNotAllowed(
                    'HTTP method is not implemented.',
                    ['GET', 'POST', 'PUT']
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

}
 