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

/**
 * Трейт для поддержки настройки сериализатора.
 */
trait TSerializerConfigurator
{

    /**
     * @var callable[] $configurators функции конфигурирования сериализатора
     */
    private $configurators = [];

    /**
     * @see ISerializerConfigurator::configureSerializer()
     */
    public function configureSerializer(ISerializer $serializer)
    {
        foreach ($this->configurators as $configurator) {
            $configurator($serializer);
        }
    }

    /**
     * @see ISerializerConfigurator::addSerializerConfigurator()
     */
    public function addSerializerConfigurator(\Closure $configurator)
    {
        $this->configurators[] = $configurator;

        return $this;
    }
}
