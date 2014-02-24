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
 * Базовый контроллер Read-Update-Delete операций над объектом.
 */
abstract class BaseRestItemController extends BaseController
{
    /**
     * Возвращает объект.
     * @param string $guid GUID объекта
     * @return mixed
     */
    abstract protected function get($guid);

    /**
     * Обновляет и возвращает объект.
     * @return mixed
     */
    abstract protected function update();

    /**
     * Удаляет объект.
     * @return mixed
     */
    abstract protected function delete();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $guid = $this->getRouteVar('guid');
                return $this->createViewResponse(
                    'item', ['item' => $this->get($guid)]
                );
            }
            case 'PUT': {
                return $this->createViewResponse(
                    'item', ['item' => $this->update()]
                );
            }
            case 'DELETE': {

            }
            case 'POST': {
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

}
 