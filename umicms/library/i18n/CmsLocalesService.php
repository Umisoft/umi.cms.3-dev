<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\i18n;

use umi\i18n\LocalesService;

/**
 * {@inheritdoc}
 */
class CmsLocalesService extends LocalesService
{
    /**
     * @var SiteLocale[] $siteLocales локали сайта
     */
    protected $siteLocales = [];
    /**
     * @var AdminLocale[] $adminLocales локали административной панели
     */
    protected $adminLocales = [];

    /**
     * Устанавливает локали, используемые на сайте.
     * @param SiteLocale[] $locales
     * @return $this
     */
    public function setSiteLocales(array $locales)
    {
        $this->siteLocales = $locales;

        return $this;
    }

    /**
     * Возвращает локали, используемые на сайте.
     * @return SiteLocale[]
     */
    public function getSiteLocales()
    {
        return $this->siteLocales;
    }

    /**
     * Устанавливает локали, используемые 0.
     * @param AdminLocale[] $locales
     * @return $this
     */
    public function setAdminLocales(array $locales)
    {
        $this->adminLocales = $locales;

        return $this;
    }

    /**
     * Возвращает локали, используемые в административной панели.
     * @return AdminLocale[]
     */
    public function getAdminLocales()
    {
        return $this->adminLocales;
    }
}
 