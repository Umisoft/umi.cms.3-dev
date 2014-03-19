<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\url;

use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\admin\component\AdminComponent;

/**
 * Интерфейс URL-менеджера.
 */
interface IUrlManager
{

    /**
     * Устанавливает базовый URL проекта.
     * @param string $baseUrl
     * @return self
     */
    public function setBaseUrl($baseUrl);

    /**
     * Устанавливает базовый URL для REST-запросов.
     * @param string $baseRestUrl
     * @return self
     */
    public function setBaseRestUrl($baseRestUrl);

    /**
     * Устанавливает базовый URL для административной панели.
     * @param string $baseAdminUrl
     * @return self
     */
    public function setBaseAdminUrl($baseAdminUrl);

    /**
     * Возвращает базовый URL проекта.
     * @return string
     */
    public function getProjectUrl();

    /**
     * Возвращает базовый URL для REST-запросов.
     * @return string
     */
    public function getBaseRestUrl();

    /**
     * Возвращает базовый URL для административной панели.
     * @return string
     */
    public function getBaseAdminUrl();

    /**
     * Возвращает URL страницы для отображения на сайте.
     * @param ICmsPage $page страница
     * @return string
     */
    public function getSitePageUrl(ICmsPage $page);

    /**
     * Возвращает URL ресурса коллекции
     * @param ICmsCollection $collection коллекция
     * @param ICmsObject|null $object
     * @return string
     */
    public function getCollectionResourceUrl(ICmsCollection $collection, ICmsObject $object = null);

    /**
     * Возвращает URL ресурса компонента
     * @param AdminComponent $component
     * @return string
     */
    public function getComponentResourceUrl(AdminComponent $component);



}
 