<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\serialization\toolbox;

use umi\toolkit\exception\UnsupportedServiceException;
use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\serialization\ISerializationAware;
use umicms\serialization\ISerializerFactory;

/**
 * Инструментарий для сериализации объектов в различные форматы.
 */
class SerializationTools implements IToolbox
{
    /**
     * Имя набора инструментов
     */
    const NAME = 'serialization';

    use TToolbox;

    /**
     * @var string $elementFactoryClass класс фабрики
     */
    public $serializerFactoryClass = 'umicms\serialization\toolbox\factory\SerializerFactory';

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->registerFactory(
            'serializer',
            $this->serializerFactoryClass,
            ['umicms\serialization\ISerializerFactory']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function injectDependencies($object)
    {
        if ($object instanceof ISerializationAware) {
            $object->setSerializerFactory($this->getSerializerFactory());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getService($serviceInterfaceName, $concreteClassName)
    {
        switch ($serviceInterfaceName) {
            case 'umicms\serialization\ISerializerFactory':
                return $this->getSerializerFactory();
        }
        throw new UnsupportedServiceException($this->translate(
            'Toolbox "{name}" does not support service "{interface}".',
            ['name' => self::NAME, 'interface' => $serviceInterfaceName]
        ));
    }

    /**
     * Возвращает фабрику элементов формы.
     * @return ISerializerFactory
     */
    protected function getSerializerFactory()
    {
        return $this->getFactory('serializer');
    }

}
