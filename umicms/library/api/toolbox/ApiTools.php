<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api\toolbox;

use umi\toolkit\exception\UnsupportedServiceException;
use umi\toolkit\toolbox\IToolbox;
use umi\toolkit\toolbox\TToolbox;
use umicms\api\IPublicApi;

/**
 * Инструментарий для работы с API.
 */
class ApiTools implements IToolbox
{
    /**
     * Имя набора инструментов
     */
    const NAME = 'api';

    use TToolbox;

    /**
     * @var array $api конфигурация API
     */
    public $api = [];

    /**
     * {@inheritdoc}
     */
    public function getService($serviceInterfaceName, $concreteClassName)
    {
        switch ($serviceInterfaceName) {
            case 'umicms\api\IPublicApi':
                return $this->getApi($concreteClassName);
        }

        throw new UnsupportedServiceException($this->translate(
            'Toolbox "{name}" does not support service "{interface}".',
            ['name' => self::NAME, 'interface' => $serviceInterfaceName]
        ));
    }

    /**
     * Возвращает API.
     * @param string $apiClassName имя класса
     * @return IPublicApi
     */
    protected function getApi($apiClassName)
    {
        $config = $this->getConfig($apiClassName);

        $apiConcreteClassName = isset($config['className']) ? $config['className'] : $apiClassName;

        return $this->getPrototype($apiConcreteClassName, [$apiClassName])
            ->createSingleInstance([], $config);
    }

    /**
     * Возвращает конфигурацию API.
     * @param string $apiClassName класс API
     * @return array
     */
    protected function getConfig($apiClassName)
    {
        return isset($this->api[$apiClassName]) ? $this->configToArray($this->api[$apiClassName], true) : [];
    }

}
