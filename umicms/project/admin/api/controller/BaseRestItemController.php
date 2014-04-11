<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\admin\api\controller;

use umi\hmvc\exception\http\HttpException;
use umi\hmvc\exception\http\HttpMethodNotAllowed;
use umi\http\Response;
use umi\orm\metadata\field\relation\BelongsToRelationField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\orm\objectset\IManyToManyObjectSet;
use umi\orm\objectset\IObjectSet;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\api\IApiAware;
use umicms\api\toolbox\TApiAware;
use umicms\exception\RuntimeException;
use umicms\exception\UnexpectedValueException;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\IRecoverableObject;
use umicms\project\module\service\api\BackupRepository;

/**
 * Базовый контроллер Read-Update-Delete операций над объектом.
 */
abstract class BaseRestItemController extends BaseRestController implements IObjectPersisterAware, IApiAware
{
    use TObjectPersisterAware;
    use TApiAware;

    /**
     * Возвращает объект.
     * @return ICmsObject
     */
    abstract protected function get();

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

                if ($object instanceof IRecoverableObject) {
                    /**
                     * @var BackupRepository $backupApi
                     */
                    $backupApi = $this->getApi('umicms\project\module\service\api\BackupRepository');
                    $backupApi->createBackup($object);
                }

                return $this->createViewResponse(
                    'update',
                    [
                        $object->getCollectionName() => $this->update($object, $this->getIncomingDataForObject($object))
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
    private function getIncomingDataForObject(ICmsObject $object)
    {
        $data = $this->getIncomingData();

        $collectionName = $object->getCollectionName();
        if (!isset($data[$collectionName]) || !is_array($data[$collectionName])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Object data not found.');
        }

        return $data[$collectionName];
    }

    /**
     * Обновляет и возвращает объект.
     * @param ICmsObject $object
     * @param array $data
     * @throws RuntimeException если невозможно сохранить объект
     * @return ICmsObject
     */
    protected function update(ICmsObject $object, array $data)
    {
        if (!isset($data[ICmsObject::FIELD_VERSION])) {
            throw new RuntimeException('Cannot save object. Object version is unknown');
        }

        $object->setVersion($data[ICmsObject::FIELD_VERSION]);

        foreach ($data as $propertyName => $value) {
            if ($object->hasProperty($propertyName) && !$object->getProperty($propertyName)->getIsReadOnly()) {

                $field = $object->getProperty($propertyName)->getField();

                switch(true) {
                    case $field instanceof HasManyRelationField: {
                        $this->setObjectSetValue($object, $propertyName, $field, $value);
                        break;
                    }
                    case $field instanceof BelongsToRelationField: {
                        $this->setObjectValue($object, $propertyName, $field, $value);
                        break;
                    }
                    case $field instanceof ManyToManyRelationField: {
                        $this->setManyToManyObjectSetValue($object, $propertyName, $field, $value);
                        break;
                    }
                    default: {
                        $object->setValue($propertyName, $value);
                    }
                }
            }
        }

        $this->getObjectPersister()->commit();

        return $object;
    }

    /**
     * Сохраняет значение объекта для HasManyRelationField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя изменяемого объекта
     * @param HasManyRelationField $field поле свойств
     * @param array $value значение (список идентификаторов связанных объектов)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setObjectSetValue(ICmsObject $object, $propertyName, HasManyRelationField $field, $value)
    {
        if (!is_array($value)) {
            throw new UnexpectedValueException(
                sprintf('Cannot set data for HasManyRelation property "%s". Data should be an array.', $propertyName)
            );
        }

        $targetCollection = $field->getTargetCollection();

        /**
         * @var IObjectSet $objectSet
         */
        $objectSet = $object->getValue($propertyName);
        /**
         * @var ICmsObject $relatedObject
         */
        foreach($objectSet as $relatedObject) {
            $relatedObject->setValue($field->getTargetFieldName(), null);
        }

        foreach ($value as $id) {
            $targetCollection->getById($id)->setValue($field->getTargetFieldName(), $object);
        }
    }

    /**
     * Сохраняет значение объекта для BelongsToRelationField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя изменяемого объекта
     * @param BelongsToRelationField $field поле свойств
     * @param int|null $value значение (идентификатор связанного объекта)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setObjectValue(ICmsObject $object, $propertyName, BelongsToRelationField $field, $value)
    {
        if (!is_numeric($value) && !is_null($value)) {
            throw new UnexpectedValueException(
                sprintf('Cannot set data for BelongsToRelation property "%s". Data should be numeric or null.', $propertyName)
            );
        }

        $value = $value ? $field->getTargetCollection()->getById($value) : null;

        $object->setValue($propertyName, $value);
    }

    /**
     * Сохраняет значение объекта для ManyToManyRelationField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя изменяемого объекта
     * @param ManyToManyRelationField $field поле свойств
     * @param array $value значение (список идентификаторов связанных объектов)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setManyToManyObjectSetValue(ICmsObject $object, $propertyName, ManyToManyRelationField $field, $value)
    {
        if (!is_array($value)) {
            throw new UnexpectedValueException(
                sprintf('Cannot set data for ManyToManyRelation property "%s". Data should be an array.', $propertyName)
            );
        }

        $targetCollection = $field->getTargetCollection();

        /**
         * @var IManyToManyObjectSet $objectSet
         */
        $objectSet = $object->getValue($propertyName);
        $objectSet->detachAll();

        foreach ($value as $id) {
            $objectSet->attach($targetCollection->getById($id));
        }
    }

}
 