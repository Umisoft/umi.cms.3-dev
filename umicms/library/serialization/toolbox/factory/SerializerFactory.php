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
            'object' => 'umicms\serialization\xml\ObjectXmlSerializer'
        ],
        self::TYPE_JSON => [

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
                [],
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
            ['type' => $type, 'class' => $objectType]
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
