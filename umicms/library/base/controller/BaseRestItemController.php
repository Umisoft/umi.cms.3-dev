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
 * Базовый контроллер Read-Update-Delete операций над объектом.
 */
abstract class BaseRestItemController extends BaseController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

    /**
     * Возвращает объект.
     * @return ICmsObject
     */
    abstract protected function get();

    /**
     * Обновляет и возвращает объект.
     * @param ICmsObject $object
     * @param array $data
     * @return ICmsObject
     */
    abstract protected function update(ICmsObject $object, array $data);

    /**
     * Удаляет объект.
     * @param ICmsObject $object
     */
    abstract protected function delete(ICmsObject $object);

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $object = $this->get();
                return $this->createViewResponse(
                    'item', [$object->getCollectionName() => $object]
                );
            }
            case 'PUT': {
                $object = $this->get();
                return $this->createViewResponse(
                    'update',
                    [
                        $object->getCollectionName() => $this->update($object, $this->getIncomingData($object))
                    ]
                );
            }
            case 'DELETE': {
                $this->delete($this->get());
                return $this->createResponse('', Response::HTTP_NO_CONTENT);
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

    /**
     * Возвращает данные для изменения объекта.
     * @param ICmsObject $object объект для изменения
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    private function getIncomingData(ICmsObject $object)
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

        $collectionName = $object->getCollectionName();
        if (!isset($data[$collectionName]) || !is_array($data[$collectionName])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Object data not found.');
        }

        return $data[$collectionName];
    }

}
 