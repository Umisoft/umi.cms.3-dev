<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\admin\api\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\behaviour\IRecoverableObject;

/**
 * Контроллер Read-Update-Delete операций над объектом.
 */
class DefaultRestItemController extends BaseDefaultRestController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        switch($this->getRequest()->getMethod()) {
            case 'GET': {
                $object = $this->getRequestedObject();
                return $this->createViewResponse(
                    'item', [$object->getCollectionName() => $object]
                );
            }
            case 'PUT': {
                $object = $this->getRequestedObject();

                $collection = $object->getCollection();
                if ($collection instanceof IRecoverableCollection && $object instanceof IRecoverableObject) {
                    $collection->createBackup($object);
                }

                return $this->createViewResponse(
                    'update',
                    [
                        $object->getCollectionName() => $this->save($object, $this->getCollectionIncomingData($object))
                    ]
                );
            }
            case 'DELETE': {
                $this->delete($this->getRequestedObject());
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
     * Возвращает запрашиваемый объект.
     * @return ICmsObject
     */
    protected function getRequestedObject()
    {
        $id = $this->getRouteVar('id');

        return $this->getComponent()->getCollection()->getById($id);
    }

    /**
     * Удаляет объект.
     * @param ICmsObject $object
     */
    protected function delete(ICmsObject $object)
    {
        $this->getComponent()->getCollection()->delete($object);
        $this->getObjectPersister()->commit();
    }

}
 