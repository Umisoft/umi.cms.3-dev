<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\json;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\serialization\exception\UnexpectedValueException;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializer;
use umicms\serialization\ISerializerFactory;
use umicms\serialization\TSerializationAware;

/**
 * Базовый сериализатор объектов в JSON
 */
abstract class BaseSerializer implements ISerializer, ISerializationAware, ILocalizable
{
    use TSerializationAware;
    use TLocalizable;

    /**
     * @var JsonWriter $jsonWriter
     */
    private static $jsonWriter;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        self::$jsonWriter = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function output()
    {
        return $this->getJsonWriter()
            ->output();
    }

    /**
     * Возвращает JsonWriter.
     * @return JsonWriter
     */
    protected function getJsonWriter()
    {
        if (!self::$jsonWriter) {
            self::$jsonWriter = new JsonWriter();
        }

        return self::$jsonWriter;
    }

    /**
     * Создает значение json-элемента
     * @param $value
     */
    protected function writeRaw($value)
    {
        $this->getJsonWriter()
            ->write($value);
    }

    /**
     * Делегирует сериализацию.
     * @param $value
     * @param array $options опции сериализации
     * @return ISerializer
     */
    protected function delegate($value, array $options = [])
    {
        $serializer = $this->getSerializer(ISerializerFactory::TYPE_JSON, $value);
        $serializer($value, $options);
    }
}
