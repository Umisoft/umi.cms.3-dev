<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\url;

use umicms\orm\object\ICmsPage;

/**
 * Интерфейс URL-менеджера.
 */
interface IUrlManager
{

    /**
     * Устанавливает базовый URL.
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl($baseUrl);

    /**
     * Возвращает базовый URL.
     * @return string
     */
    public function getBaseUrl();

    /**
     * Возвращает URL страницы для отображения на сайте.
     * @param ICmsPage $page страница
     * @return string
     */
    public function getSiteUrl(ICmsPage $page);

}
 