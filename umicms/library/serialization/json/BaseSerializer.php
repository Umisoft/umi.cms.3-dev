<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization\json;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
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
