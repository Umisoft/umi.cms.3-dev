<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\core;

use umi\config\entity\IConfig;
use umicms\exception\RequiredDependencyException;

/**
 * Трейт для работы с конфигурацией проекта UMI.CMS
 */
trait TProjectConfigAware
{
    /**
     * @var IConfig $_config
     */
    private $_config;

    /**
     * Устанавливает конфигурацию сайта.
     * @param IConfig $config
     */
    public function setConfig(IConfig $config) {
        $this->_config = $config;
    }

    /**
     * Возвращает конфигурацию сайта.
     * @throws RequiredDependencyException если конфигурация не была установлена
     * @return IConfig
     */
    protected function getConfig() {
        if (!$this->_config) {
            throw new RequiredDependencyException(sprintf(
                'Site configuration is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->_config;
    }

}
