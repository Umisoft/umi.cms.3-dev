<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\url;

use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\settings\component\SettingsComponent;

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
     * Устанавливает префикс URL для запросов связанных с настройками.
     * @param string $settingsUrlPrefix
     * @return self
     */
    public function setSettingsUrlPrefix($settingsUrlPrefix);

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
     * Возвращает базовый URL для запросов связанных с настройками.
     * @return string
     */
    public function getBaseSettingsUrl();

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
     * @param array $params список GET-параметров
     * @return string
     */
    public function getAdminComponentActionResourceUrl(AdminComponent $component, $actionName, array $params = []);

    /**
     * Возвращает URL ресурса компонента настроек.
     * @param SettingsComponent $component
     * @return string
     */
    public function getSettingsComponentResourceUrl(SettingsComponent $component);

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
 