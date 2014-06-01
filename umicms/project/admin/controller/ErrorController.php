<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\controller;

use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\hmvc\controller\BaseController;
use umicms\exception\NonexistentEntityException;

/**
 * Контроллер ошибок для административной панели.
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
            case $this->exception instanceof ResourceAccessForbiddenException:
                return Response::HTTP_FORBIDDEN;
            case $this->exception instanceof HttpException:
                return $this->exception->getCode();
            default:
                return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}