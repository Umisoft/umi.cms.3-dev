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
     * Устанавливает URL домена проекта.
     * @param string $domainUrl
     * @return self
     */
    public function setProjectDomainUrl($domainUrl);

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
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getProjectUrl($isAbsolute = false);

    /**
     * Возвращает URL домена проекта.
     * @return string
     */
    public function getProjectDomainUrl();

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
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getSitePageUrl(ICmsPage $page, $isAbsolute = false);

    /**
     * Возвращает URL системной страницы по пути ее компонента-обработчика
     * @param string $componentPath путь ее компонента-обработчика
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @throws NonexistentEntityException если такой страницы нет
     * @return string
     */
    public function getSystemPageUrl($componentPath, $isAbsolute = false);

    /**
     * Возвращает URL компонента в административной панели.
     * @param AdminComponent $component
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    public function getAdminComponentUrl(AdminComponent $component, $isAbsolute = false);

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
     * Возвращает URL ресурса компонента
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
 