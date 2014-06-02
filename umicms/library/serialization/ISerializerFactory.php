<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\serialization;

use umicms\serialization\exception\OutOfBoundsException;
use umicms\serialization\exception\UnexpectedValueException;

/**
 * Интерфейс фабрики сериализаторов
 */
interface ISerializerFactory
{
    /**
     * Тип сериализации в XML
     */
    const TYPE_XML = 'xml';
    /**
     * Тип сериализации в JSON
     */
    const TYPE_JSON = 'json';


    /**
     * Возвращает сериализатор указанного типа для объекта
     * @param string $type тип сериализации
     * @param mixed $object
     * @throws OutOfBoundsException если сериализатор для указанного типа и объекта не зарегистрирован
     * @throws UnexpectedValueException если сериализатор не callable
     * @return ISerializer|callable
     */
    public function getSerializer($type, $object);

    /**
     * Регистрирует сериализаторы
     * @param array $serializers конфигурация сериализаторов в формате [$type => [$instanceClass => $serializerClass, ...], ...]
     * @return self
     */
    public function registerSerializers(array $serializers);
}
