<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\route;

use umi\http\Request;
use umi\route\type\FixedRoute;

/**
 * Правило маршрутизатора для определения текущего проекта.
 */
class ProjectHostRoute extends FixedRoute
{
    /**
     * Название опции для задания схемы маршрута
     */
    const OPTION_SCHEME = 'scheme';
    /**
     * Название опции для задания хоста маршрута
     */
    const OPTION_HOST = 'host';
    /**
     * Название опции для задания порта маршрута
     */
    const OPTION_PORT = 'port';
    /**
     * Название опции для задания префикса относительных URL
     */
    const OPTION_PREFIX = 'prefix';

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = [], array $subroutes = [], Request $request)
    {
        parent::__construct($options, $subroutes);

        $scheme = isset($this->defaults[self::OPTION_SCHEME]) ? $this->defaults[self::OPTION_SCHEME] : $request->getScheme();
        $host = isset($this->defaults[self::OPTION_HOST]) ? $this->defaults[self::OPTION_HOST] : $request->getHost();
        $port = isset($this->defaults[self::OPTION_PORT]) ? $this->defaults[self::OPTION_PORT] : $request->getPort();
        $prefix = isset($this->defaults[self::OPTION_PREFIX]) ? $this->defaults[self::OPTION_PREFIX] : '';


        $this->params = [
            self::OPTION_SCHEME => $scheme,
            self::OPTION_HOST => $host,
            self::OPTION_PORT => $port,
            self::OPTION_PREFIX => $prefix
        ];

        $portPostfix = '';
        if ($port && $port != 80) {
            $portPostfix = ':' . $port;
        }

        $this->route = $scheme . '://' . $host . $portPostfix . $prefix;

    }
}
 