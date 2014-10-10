<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\url;

use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Интерфейс URL-менеджера.
 */
interface IUrlManager
{

    /**
     * Устанавливает префикс URL проекта.
     * @param string $urlPrefix
     * @return self
     */
    public function setUrlPrefix($urlPrefix);

    /**
     * Устанавливает постфикс URL проекта.
     * @param string $urlPostfix
     * @return self
     */
    public function setSiteUrlPostfix($urlPostfix);

    /**
     * Устанавливает базовый URL для общих ассетов (js/css) проектов.
     * @param string $assetsUrl
     * @return self
     */
    public function setAdminAssetsUrl($assetsUrl);

    /**
     * Устанавливает базовый URL для ассетов (js/css) проекта.
     * @param string $assetsUrl
     * @return self
     */
    public function setProjectAssetsUrl($assetsUrl);

    /**
     * Устанавливает схему и HTTP-хост проета.
     * @param string $schemeAndHttpHost
     * @return self
     */
    public function setSchemeAndHttpHost($schemeAndHttpHost);

    /**
     * Устанавливает префикс URL для REST-запросов.
     * @param string $restUrlPrefix
     * @return self
     */
    public function setRestUrlPrefix($restUrlPrefix);

    /**
     * Устанавливает префикс URL для административной панели.
     * @param string $adminUrlPrefix
     * @return self
     */
    public function setAdminUrlPrefix($adminUrlPrefix);

    /**
     * Возвращает базовый URL проекта.
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getProjectUrl($isAbsolute = false);

    /**
     * Возвращает базовый URL ассетов (js/css) проекта.
     * @return string
     */
    public function getProjectAssetsUrl();

    /**
     * Возвращает базовый URL административных ассетов (js/css).
     * @return string
     */
    public function getAdminAssetsUrl();

    /**
     * Возвращает постфикс для URL проекта.
     * @return string|null
     */
    public function getSiteUrlPostfix();

    /**
     * Возвращает схему и HTTP-хост проета.
     * @return string
     */
    public function getSchemeAndHttpHost();

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
     * Возвращает URL страницы без учета префиксов.
     * @param ICmsPage $page страница
     * @param string $handler имя компонента-обработчика страницы
     * @throws RuntimeException если компонент не умеет формировать URL для страниц
     * @return string
     */
    public function getRawPageUrl(ICmsPage $page, $handler = ICmsCollection::HANDLER_SITE);

    /**
     * Возвращает URL системной страницы по пути ее компонента-обработчика без учета префиксов.
     * @param string $componentPath путь ее компонента-обработчика
     * @return string
     */
    public function getRawSystemPageUrl($componentPath);

    /**
     * Возвращает URL страницы для отображения на сайте.
     * @param ICmsPage $page страница
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @param string $handler имя компонента-обработчика страницы
     * @return string
     */
    public function getSitePageUrl(ICmsPage $page, $isAbsolute = false, $handler = ICmsCollection::HANDLER_SITE);

    /**
     * Возвращает URL системной страницы по пути ее компонента-обработчика.
     * @param string $componentPath путь ее компонента-обработчика
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @throws NonexistentEntityException если такой страницы нет
     * @return string
     */
    public function getSystemPageUrl($componentPath, $isAbsolute = false);

    /**
     * Возвращает ссылку на редактирование объекта в административной панели.
     * @param ICmsObject $object
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getObjectEditLinkUrl(ICmsObject $object, $isAbsolute = false);

    /**
     * Возвращает URL ресурса коллекции
     * @param ICmsCollection $collection коллекция
     * @param ICmsObject|null $object
     * @return string
     */
    public function getCollectionResourceUrl(ICmsCollection $collection, ICmsObject $object = null);

    /**
     * Возвращает URL компонента в административной панели.
     * @param AdminComponent $component
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getAdminComponentUrl(AdminComponent $component, $isAbsolute = false);

    /**
     * Возвращает URL ресурса компонента.
     * @param AdminComponent $component
     * @return string
     */
    public function getAdminComponentResourceUrl(AdminComponent $component);

    /**
     * Возвращает URL действия компонента
     * @param AdminComponent $component
     * @param string $actionName имя действия
     * @param array $params список GET-параметров. Значение может быть плейсхолдером, который будет обработан на клиенте.
     * @return string
     */
    public function getAdminComponentActionResourceUrl(AdminComponent $component, $actionName, array $params = []);

    /**
     * Возвращает текущий URL запроса.
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getCurrentUrl($isAbsolute = false);

    /**
     * Возвращает текущий URL запроса с подменой значения GET-параметра.
     * @param string $paramName
     * @param mixed $paramValue
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getCurrentUrlWithParam($paramName, $paramValue, $isAbsolute = false);

}
 