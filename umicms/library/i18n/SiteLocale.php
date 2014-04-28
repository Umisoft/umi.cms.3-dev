<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\i18n;

/**
 * Локаль сайта
 */
class SiteLocale extends BaseLocale
{
    /**
     * @var string $url URL сайта для локали по умолчанию
     */
    protected $url;

    /**
     * Устанавливает URL сайта для локали по умолчанию
     * @param string $url URL
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Возвращает URL сайта для локали по умолчанию
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

}
 