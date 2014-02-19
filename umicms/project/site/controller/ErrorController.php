<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpForbidden;
use umi\hmvc\exception\http\HttpNotFound;
use umi\http\Response;
use umicms\library\controller\BaseController;

/**
 * Контроллер ошибок для сайта.
 */
class ErrorController extends BaseController
{

    /**
     * @var \Exception $exception
     */
    protected $exception;

    /**
     * Конструктор.
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {

        if ($this->exception instanceof HttpNotFound) {
             return $this->error404();
        }

        if ($this->exception instanceof HttpForbidden) {
            return $this->error403();
        }

        $code = HttpException::HTTP_INTERNAL_SERVER_ERROR;
        if ($this->exception instanceof HttpException) {
            $code = $this->exception->getCode();
        }

        $e = $this->exception;
        $stack = [];

        while ($e = $e->getPrevious()) {
            $stack[] = $e;
        }

        return $this->createViewResponse(
            'error/runtime',
            [
                'e' => $this->exception,
                'stack' => $stack
            ]
        )
            ->setStatusCode($code);
    }

    /**
     * Отображает 404 ошибку.
     * @return Response
     */
    public function error404()
    {
        return $this->createViewResponse(
            'error/404',
            [
                'e' => $this->exception
            ]
        );
            //->setStatusCode($this->exception->getCode());
    }

    /**
     * Отображает 403 ошибку.
     * @return Response
     */
    public function error403()
    {
        return $this->createViewResponse(
            'error/403',
            [
                'e' => $this->exception
            ]
        )
            ->setStatusCode($this->exception->getCode());
    }
}