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
use umi\http\Response;
use umi\orm\metadata\field\datetime\DateTimeField;
use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\field\relation\ManyToManyRelationField;
use umi\orm\objectset\IManyToManyObjectSet;
use umi\orm\objectset\IObjectSet;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\exception\RuntimeException;
use umicms\exception\UnexpectedValueException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\metadata\field\relation\BelongsToRelationField;
use umicms\orm\object\ICmsObject;
use umicms\project\admin\api\component\CollectionApiComponent;
use umicms\project\admin\controller\base\BaseAdminController;

/**
 * Базовый REST-контроллер.
 */
abstract class BaseDefaultRestController extends BaseAdminController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

    /**
     * Возвращает компонент, у которого вызван контроллер.
     * @throws RuntimeException при неверном классе компонента
     * @return CollectionApiComponent
     */
    protected function getComponent()
    {
        $component = parent::getComponent();

        if (!$component instanceof CollectionApiComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Component for controller "{controllerClass}" should be instance of "{componentClass}".',
                    [
                        'controllerClass' => get_class($this),
                        'componentClass' => 'umicms\project\admin\api\component\CollectionApiComponent'
                    ]
                )
            );
        }

        return $component;
    }

    /**
     * Возвращает коллекцию, с которой работает компонент.
     * @return ICmsCollection
     */
    protected function getCollection()
    {
        return $this->getComponent()->getCollection();
    }

    /**
     * Возвращает имя коллекции, с которой работает компонент.
     * @return string
     */
    protected function getCollectionName()
    {
        return $this->getComponent()->getCollection()->getName();
    }

    /**
     * Возвращает данные для сохранения объекта.
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    protected function getCollectionIncomingData()
    {
        $data = $this->getIncomingData();

        $collectionName = $this->getCollectionName();
        if (!isset($data[$collectionName]) || !is_array($data[$collectionName])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Object data not found.');
        }

        return $data[$collectionName];
    }

    /**
     * Возвращает данные, полученные в теле POST- или PUT-запроса, в виде массива.
     * @throws HttpException если не удалось получить данные
     * @return array
     */
    protected function getIncomingData()
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

        return $data;
    }

    /**
     * Обновляет и возвращает объект.
     * @param ICmsObject $object
     * @param array $data
     * @throws RuntimeException если невозможно сохранить объект
     * @return ICmsObject
     */
    protected function save(ICmsObject $object, array $data)
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
                    case $field instanceof DateTimeField: {
                        $this->setDateTimeValue($object, $propertyName, $value);
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
     * Сохраняет значение объекта для DateTimeField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя свойства изменяемого объекта
     * @param array|null $value
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setDateTimeValue(ICmsObject $object, $propertyName, $value)
    {
        if (!is_null($value)) {
            if (!is_array($value) || !isset($value['date'])) {
                throw new UnexpectedValueException(
                    $this->translate(
                        'Cannot set data for DateTime property "{propertyName}". Data should be null or an array and contain "date" option.',
                        ['propertyName' => $propertyName]
                    )
                );
            }
            $object->setValue($propertyName, new \DateTime($value['date']));
        } else {
            $object->setValue($propertyName, null);
        }
    }

    /**
     * Сохраняет значение объекта для HasManyRelationField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя свойства изменяемого объекта
     * @param HasManyRelationField $field поле свойства
     * @param array $value значение (список идентификаторов связанных объектов)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setObjectSetValue(ICmsObject $object, $propertyName, HasManyRelationField $field, $value)
    {
        if (!is_array($value)) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot set data for HasManyRelation property "{propertyName}". Data should be an array.',
                    ['propertyName' => $propertyName]
                )
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
     * @param string $propertyName имя свойства изменяемого объекта
     * @param BelongsToRelationField $field поле свойства
     * @param int|null $value значение (идентификатор связанного объекта)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setObjectValue(ICmsObject $object, $propertyName, BelongsToRelationField $field, $value)
    {
        if (!is_numeric($value) && !is_null($value)) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot set data for BelongsToRelation property "{propertyName}". Data should be numeric or null.',
                    ['propertyName' => $propertyName]
                )
            );
        }

        $value = $value ? $field->getTargetCollection()->getById($value) : null;

        $object->setValue($propertyName, $value);
    }

    /**
     * Сохраняет значение объекта для ManyToManyRelationField.
     * @param ICmsObject $object изменяемый объект
     * @param string $propertyName имя свойства изменяемого объекта
     * @param ManyToManyRelationField $field поле свойства
     * @param array $value значение (список идентификаторов связанных объектов)
     * @throws UnexpectedValueException если значение некорректно
     */
    protected function setManyToManyObjectSetValue(ICmsObject $object, $propertyName, ManyToManyRelationField $field, $value)
    {
        if (!is_array($value)) {
            throw new UnexpectedValueException(
                $this->translate(
                    'Cannot set data for ManyToManyRelation property "{propertyName}". Data should be an array.',
                    ['propertyName' => $propertyName]
                )
            );
        }

        $targetCollection = $field->getTargetCollection();

        /**
         * @var IManyToManyObjectSet $objectSet
         */
        $objectSet = $object->getValue($propertyName);
        $objectSet->detachAll();

        foreach ($value as $id) {
            $objectSet->link($targetCollection->getById($id));
        }
    }

}
 