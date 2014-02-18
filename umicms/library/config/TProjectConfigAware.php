<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\config;

use umi\config\entity\IConfig;
use umicms\exception\RequiredDependencyException;

/**
 * Трейт для работы с конфигурацией проекта UMI.CMS
 */
trait TProjectConfigAware
{
    /**
     * @var IConfig $traitProjectConfig
     */
    private $traitProjectConfig;

    /**
     * Устанавливает конфигурацию проекта.
     * @param IConfig $config
     */
    public function setProjectConfig(IConfig $config) {
        $this->traitProjectConfig = $config;
    }

    /**
     * Возвращает конфигурацию проекта.
     * @throws RequiredDependencyException если конфигурация не была установлена
     * @return IConfig
     */
    protected function getProjectConfig() {
        if (!$this->traitProjectConfig) {
            throw new RequiredDependencyException(sprintf(
                'Project configuration is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitProjectConfig;
    }

}
