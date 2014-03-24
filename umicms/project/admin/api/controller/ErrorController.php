<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\hmvc\controller\BaseController;
use umicms\exception\NonexistentEntityException;

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
        $e = $this->exception;
        $stack = [];

        while ($e = $e->getPrevious()) {
            $stack[] = $e;
        }

        $code = $this->getHttpStatusCode();

        return $this->createViewResponse(
            'error',
            [
                'error' => $this->exception,
                'code' => $code,
                'stack' => $stack
            ]
        )
            ->setStatusCode($code);
    }

    /**
     * Определяет код статуса ответа по произошедшему исключению.
     * @return int
     */
    protected function getHttpStatusCode()
    {
        switch(true) {
            case $this->exception instanceof NonexistentEntityException:
                return Response::HTTP_NOT_FOUND;
            case $this->exception instanceof HttpException:
                return $this->exception->getCode();
            default:
                return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}