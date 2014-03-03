<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\api;

use umi\i18n\ILocalizable;
use umi\toolkit\factory\IFactory;
use umi\toolkit\factory\TFactory;

/**
 * Базовый класс для реализации составных API.
 */
abstract class BaseComplexApi implements ILocalizable, IFactory
{
    use TFactory;

    /**
     * Опции дочерних API.
     * @var array $options
     */
    public $options = [];

    /**
     * Возвращает дочернее API.
     * @param string $apiClassName имя класса
     * @return object
     */
    protected function getApi($apiClassName)
    {
        $options = $this->getApiOptions($apiClassName);

        $apiConcreteClassName = isset($options['className']) ? $options['className'] : $apiClassName;

        return $this->getPrototype($apiConcreteClassName, [$apiClassName])
            ->createSingleInstance([], $options);
    }

    /**
     * Возвращает опции для API
     * @param string $apiClassName имя класса
     * @return array
     */
    private function getApiOptions($apiClassName) {
        return isset($this->options[$apiClassName]) ? $this->configToArray($this->options[$apiClassName], true) : [];
    }
}
