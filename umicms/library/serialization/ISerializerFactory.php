<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
     * @return callable
     */
    public function getSerializer($type, $object);
}
