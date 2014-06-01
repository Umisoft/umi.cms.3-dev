<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 