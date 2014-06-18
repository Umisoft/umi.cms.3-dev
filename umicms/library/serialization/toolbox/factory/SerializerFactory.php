<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
            'NULL' => 'umicms\serialization\xml\NullSerializer'
        ],
        self::TYPE_JSON => [
            'umicms\exception\InvalidObjectsException' => 'umicms\serialization\json\InvalidObjectsExceptionSerializer',
            'Exception' => 'umicms\serialization\json\ExceptionSerializer',
            'object' => 'umicms\serialization\json\ObjectSerializer',
            'array' => 'umicms\serialization\json\ArraySerializer',
            // scalar types
            'boolean' => 'umicms\serialization\json\ScalarSerializer',
            'integer' => 'umicms\serialization\json\ScalarSerializer',
            'double' => 'umicms\serialization\json\ScalarSerializer',
            'string' => 'umicms\serialization\json\ScalarSerializer',
            // null
            'NULL' => 'umicms\serialization\json\NullSerializer'

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
     * {@inheritdoc}
     */
    public function registerSerializers(array $serializers)
    {
        $this->types = array_merge_recursive($this->types, $serializers);

        return $this;
    }

    /**
     * Возвращает класс декоратора для указанного объекта, если декоратор не найден,
     * возвращает класс по умолчанию.
     * @param string $type тип сериализации
     * @param object $object экземпляр объекта
     * @throws OutOfBoundsException если тип сериализации или сериализатор не зарегистрирован
     * @return string
     */
    private function resolveSerializerClass($type, $object)
    {
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
    private function getSerializerByClassName($type, $objectType)
    {
        return isset($this->types[$type][$objectType]) ? $this->types[$type][$objectType] : null;
    }

}
