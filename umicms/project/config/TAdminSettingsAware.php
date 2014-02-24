<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\config;

use umi\config\entity\IConfig;
use umicms\exception\RequiredDependencyException;

/**
 * Трейт для работы с настройками административной панели
 */
trait TAdminSettingsAware
{
    /**
     * @var IConfig $traitAdminSettings
     */
    private $traitAdminSettings;

    /**
     * @see IAdminSettingsAware::setAdminSettings()
     */
    public function setAdminSettings(IConfig $config)
    {
        $this->traitAdminSettings = $config;
    }

    /**
     * Возвращает настройки административной панели.
     * @throws RequiredDependencyException если настройки не были установлены
     * @return IConfig
     */
    protected function getSiteSettings()
    {
        if (!$this->traitAdminSettings) {
            throw new RequiredDependencyException(sprintf(
                'Administration panel settings is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitAdminSettings;
    }

}
