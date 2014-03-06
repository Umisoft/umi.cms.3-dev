<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\toolbox\factory;

use ReflectionClass;
use umi\toolkit\factory\IFactory;
use umi\toolkit\factory\TFactory;
use umi\toolkit\prototype\IPrototype;
use umicms\exception\UnexpectedValueException;
use umicms\serialization\exception\OutOfBoundsException;
use umicms\serialization\ISerializerFactory;

/**
 * Фабрика сериализаторов.
 */
class SerializerFactory implements IFactory, ISerializerFactory
{
    use TFactory;

    /**
     * @var array $types список сериализаторов
     */
    public $types = [
        self::TYPE_XML => [

            'Exception' => 'umicms\serialization\xml\ExceptionSerializer',
            'object' => 'umicms\serialization\xml\ObjectSerializer',
            'array' => 'umicms\serialization\xml\ArraySerializer',
            // scalar types
            'boolean' => 'umicms\serialization\xml\ScalarSerializer',
            'integer' => 'umicms\serialization\xml\ScalarSerializer',
            'double' => 'umicms\serialization\xml\ScalarSerializer',
            'string' => 'umicms\serialization\xml\ScalarSerializer',
            // null
            'NULL' => 'umicms\serialization\xml\NullSerializer',
            // cms objects
            'umicms\orm\object\CmsObject' => 'umicms\serialization\xml\object\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\xml\object\CmsElementSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\xml\object\FieldSerializer'

        ],
        self::TYPE_JSON => [
            'Exception' => 'umicms\serialization\json\ExceptionSerializer',
            'object' => 'umicms\serialization\json\ObjectSerializer',
            'array' => 'umicms\serialization\json\ArraySerializer',
            // scalar types
            'boolean' => 'umicms\serialization\json\ScalarSerializer',
            'integer' => 'umicms\serialization\json\ScalarSerializer',
            'double' => 'umicms\serialization\json\ScalarSerializer',
            'string' => 'umicms\serialization\json\ScalarSerializer',
            // null
            'NULL' => 'umicms\serialization\json\NullSerializer',
            // orm
            'umi\orm\collection\BaseCollection' => 'umicms\serialization\json\orm\CollectionSerializer',
            'umi\orm\metadata\Metadata' => 'umicms\serialization\json\orm\MetadataSerializer',
            'umi\orm\metadata\ObjectType' => 'umicms\serialization\json\orm\ObjectTypeSerializer',
            'umi\orm\metadata\field\BaseField' => 'umicms\serialization\json\orm\FieldSerializer',
            'umicms\orm\object\CmsObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umicms\orm\object\CmsHierarchicObject' => 'umicms\serialization\json\orm\CmsObjectSerializer',
            'umi\orm\selector\Selector' => 'umicms\serialization\json\orm\SelectorSerializer',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function getSerializer($type, $object)
    {
        $serializerClass = $this->resolveSerializerClass($type, $object);

        return $this
            ->getPrototype(
                $serializerClass,
                ['umicms\serialization\ISerializer'],
                function (IPrototype $prototype) {
                    /** @noinspection PhpParamsInspection */
                    if (!is_callable($prototype->getPrototypeInstance())) {
                        throw new UnexpectedValueException(
                            $this->translate(
                                'Serializer "{class}" should be callable.',
                                ['class' => $prototype->getClassName()]
                            )
                        );
                    }
                }
            )
            ->createSingleInstance();
    }

    /**
     * Возвращает класс декоратора для указанного объекта, если декоратор не найден,
     * возвращает класс по умолчанию.
     * @param string $type тип сериализации
     * @param object $object экземпляр объекта
     * @throws OutOfBoundsException если тип сериализации или сериализатор не зарегистрирован
     * @return string
     */
    private function resolveSerializerClass($type, $object) {
        if (!isset($this->types[$type])) {
            throw new OutOfBoundsException($this->translate(
                'Serialization type "{type}" is not registered.',
                ['type' => $type]
            ));
        }


        $objectType = gettype($object);

        if (is_object($object)) {
            $objectClass = get_class($object);
            if ($serializerClass = $this->getSerializerByClassName($type, $objectClass)) {
                return $serializerClass;
            }

            $parentClass = new ReflectionClass($object);
            while ($parentClass = $parentClass->getParentClass()) {
                if ($serializerClass = $this->getSerializerByClassName($type, $parentClass->getName())) {
                    return $this->types[$type][$objectClass] = $serializerClass;
                }
            }
        }

        if ($serializerClass = $this->getSerializerByClassName($type, $objectType)) {
            return $serializerClass;
        }


        throw new OutOfBoundsException($this->translate(
            'Serializer for type "{type}" and object type "{objectType}" is not registered.',
            ['type' => $type, 'objectType' => $objectType]
        ));
    }

    /**
     * Возвращает сериализатор, либо null
     * @param string $type тип сериализации
     * @param string $objectType имя класса либо тип объекта
     * @return string|null
     */
    private function getSerializerByClassName($type, $objectType) {
        return isset($this->types[$type][$objectType]) ? $this->types[$type][$objectType] : null;
    }

}
