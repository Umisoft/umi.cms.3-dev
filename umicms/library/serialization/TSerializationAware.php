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

use umicms\serialization\exception\RequiredDependencyException;

/**
 * Трейт для поддержки сериализации объектов в различные форматы.
 */
trait TSerializationAware
{
    /**
     * @var ISerializerFactory $traitSerializerFactory логгер
     */
    private $traitSerializerFactory;

    /**
     * @see ISerializationAware::setSerializerFactory()
     */
    public function setSerializerFactory(ISerializerFactory $serializerFactory)
    {
        $this->traitSerializerFactory = $serializerFactory;
    }

    /**
     * Возвращает сериализатор объекта в строку.
     * @param string $type тип сериализации
     * @param mixed $object
     * @return ISerializer|callable
     */
    protected function getSerializer($type, $object)
    {
        return $this->getSerializerFactory()->getSerializer($type, $object);
    }

    /**
     * Возвращает фабрику сериализаторов.
     * @throws RequiredDependencyException если фабрика не внедрена
     * @return ISerializerFactory
     */
    private function getSerializerFactory()
    {
        if (!$this->traitSerializerFactory) {
            throw new RequiredDependencyException(sprintf(
                'Serializer factory is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitSerializerFactory;
    }
}
