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
use umicms\project\site\SiteApplication;

/**
 * Трейт для работы с настройками сайта
 */
trait TSiteSettingsAware
{
    /**
     * @var IConfig $traitSiteSettings
     */
    private $traitSiteSettings;

    /**
     * @see ISiteSettingsAware::setSiteSettings()
     */
    public function setSiteSettings(IConfig $config)
    {
        $this->traitSiteSettings = $config;
    }

    /**
     * Возвращает настройки сайта.
     * @throws RequiredDependencyException если настройки не были установлены
     * @return IConfig
     */
    protected function getSiteSettings()
    {
        if (!$this->traitSiteSettings) {
            throw new RequiredDependencyException(sprintf(
                'Site settings is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitSiteSettings;
    }

    /**
     * Возвращает постфикс для URL сайта
     * @return string|null
     */
    protected function getSiteUrlPostfix()
    {
        return $this->getSiteSettings()->get(SiteApplication::SETTING_URL_POSTFIX);
    }

    /**
     * Возвращает GUID страницы сайта по умолчанию.
     * @return string
     */
    protected function getSiteDefaultPageGuid()
    {
        return $this->getSiteSettings()->get(SiteApplication::SETTING_DEFAULT_PAGE_GUID);
    }

}
