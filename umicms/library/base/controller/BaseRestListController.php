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
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\base\object\ICmsObject;

/**
 * Базовый контроллер действий над списком.
 */
abstract class BaseRestListController extends BaseController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

    /**
     * Возвращает список.
     * @return \Traversable
     */
    abstract protected function getList();

    /**
     * Создает и возвращает объект списка.
     * @param array $data данные
     * @return ICmsObject
     */
    abstract protected function create(array $data);

    /**
     * Возвращает имя коллекции.
     * @return string
     */
    abstract protected function getCollectionName();

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                return $this->createViewResponse(
                    'list', [$this->getCollectionName() => $this->getList()]
                );
            }
            case 'PUT':
            case 'POST': {
                $object = $this->create($this->getIncomingData());
                return $this->createViewResponse(
                    'item', [$this->getCollectionName() => $object]
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

    /**
     * Возвращает данные для изменения объекта.
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    private function getIncomingData()
    {
        $inputData = file_get_contents('php://input');
        if (!$inputData) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request body is empty.');
        }

        $data = @json_decode($inputData, true);

        if ($error = json_last_error()) {
            if (function_exists('json_last_error_msg')) {
                $error = json_last_error_msg();
            }
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'JSON parse error: ' . $error);
        }

        if (!isset($data[$this->getCollectionName()]) || !is_array($data[$this->getCollectionName()])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Object data not found.');
        }

        return $data[$this->getCollectionName()];
    }

}
 