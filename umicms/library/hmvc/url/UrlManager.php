<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\url;

use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\RuntimeException;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\admin\component\AdminComponent;
use umicms\project\admin\settings\component\SettingsComponent;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\site\component\BaseDefaultSitePageComponent;

/**
 * URL-менеджер.
 */
class UrlManager implements IUrlManager, ILocalizable
{
    use TLocalizable;

    /**
     * @var CmsDispatcher $dispatcher диспетчер компонентов
     */
    protected $dispatcher;
    /**
     * @var StructureModule $structureApi API структуры сайта
     */
    protected $structureApi;
    /**
     * @var string $baseUrl базовый URL проекта
     */
    protected $baseUrl = '/';
    /**
     * @var string $baseRestUrl базовый URL для REST-запросов
     */
    protected $baseRestUrl = '/';
    /**
     * @var string $baseSettingsUrl базовый URL для запросов связанных с настройками
     */
    protected $baseSettingsUrl = '/';
    /**
     * @var string $baseAdminUrl базовый URL для административной панели
     */
    protected $baseAdminUrl;
    /**
     * @var string $domainUrl URL домена проекта
     */
    protected $domainUrl;

    /**
     * Конструктор.
     * @param CmsDispatcher $dispatcher диспетчер компонентов
     * @param StructureModule $structureApi
     */
    public function __construct(CmsDispatcher $dispatcher, StructureModule $structureApi)
    {
        $this->dispatcher = $dispatcher;
        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProjectDomainUrl($domainUrl)
    {
        $this->domainUrl = $domainUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseRestUrl($baseRestUrl)
    {
        $this->baseRestUrl = $baseRestUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseSettingsUrl($baseSettingsUrl)
    {
        $this->baseSettingsUrl = $baseSettingsUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseAdminUrl($baseAdminUrl)
    {
        $this->baseAdminUrl = $baseAdminUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProjectUrl($isAbsolute = false)
    {
        if ($isAbsolute) {
            return $this->getProjectDomainUrl() . $this->baseUrl;
        }

        return $this->baseUrl ?: '/';
    }

    /**
     * {@inheritdoc}
     */
    public function getProjectDomainUrl()
    {
        return $this->domainUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseRestUrl()
    {
        return $this->baseRestUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseSettingsUrl()
    {
        return $this->baseSettingsUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAdminUrl()
    {
        return $this->baseAdminUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getSitePageUrl(ICmsPage $page, $isAbsolute = false, $handler = ICmsCollection::HANDLER_SITE)
    {
        if ($page instanceof StructureElement) {
            $pageUrl = $isAbsolute ? $this->domainUrl : '';
            $pageUrl .= $this->baseUrl . '/';
            $pageUrl .= $page->getURL();

            return $pageUrl;
        }
        /**
         * @var ICmsCollection $collection
         */
        $collection = $page->getCollection();
        $handler = $collection->getHandlerPath($handler);

        $component = $this->dispatcher->getSiteComponentByPath($handler);
        if (!$component instanceof BaseDefaultSitePageComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get url for page with GUID "{guid}". Component "{path}" shoul be instance of "{class}".',
                    [
                        'guid' => $page->getGUID(),
                        'path' => $component->getPath(),
                        'class' => 'umicms\project\site\component\BaseDefaultSitePageComponent'
                    ]
                )
            );
        }

        return $this->getSystemPageUrl($handler, $isAbsolute) . $component->getPageUri($page);
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemPageUrl($componentPath, $isAbsolute = false)
    {
        $pageUrl = $isAbsolute ? $this->domainUrl : '';
        $pageUrl .= $this->baseUrl . '/';
        $pageUrl .= $this->structureApi->element()->getSystemPageByComponentPath($componentPath)->getURL();

        return $pageUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectEditLinkUrl(ICmsObject $object, $isAbsolute = false)
    {
        /**
         * @var ICmsCollection $collection
         */
        $collection = $object->getCollection();

        $editLink = $isAbsolute ? $this->domainUrl : '';
        $editLink .= $this->baseAdminUrl;
        $editLink .= '/' . str_replace('.', '/', $collection->getHandlerPath('admin'));
        $editLink .= '/form/' . $object->getId();

        return $editLink;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollectionResourceUrl(ICmsCollection $collection, ICmsObject $object = null)
    {
        $collectionResourceUrl = $this->baseRestUrl;
        $collectionResourceUrl .= '/' . str_replace('.', '/', $collection->getHandlerPath('admin'));
        $collectionResourceUrl .= '/collection';

        if ($object) {
            $collectionResourceUrl .= '/' . $object->getId();
        }

        return $collectionResourceUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentUrl(AdminComponent $component, $isAbsolute = false)
    {
        $domainUrl = $isAbsolute ? $this->domainUrl : '';

        return $domainUrl . $this->baseAdminUrl . $this->getAdminRelativeComponentUrl($component);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentResourceUrl(AdminComponent $component)
    {
        return $this->baseRestUrl . $this->getAdminRelativeComponentUrl($component);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentActionResourceUrl(AdminComponent $component, $actionName, array $params = [])
    {
        $actionUrl = $this->getAdminComponentResourceUrl($component);
        $actionUrl .= $component->getRouter()->assemble('action', ['action' => $actionName]);

        if ($params) {
            $actionUrl .= '?' . http_build_query($params);
        }

        return $actionUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsComponentResourceUrl(SettingsComponent $component)
    {
        $url = $this->baseSettingsUrl;
        $url .= str_replace(SettingsComponent::PATH_SEPARATOR, '/', substr($component->getPath(), 22));

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentUrl($isAbsolute = false)
    {
        if (null !== $qs = $this->getQueryString()) {
            $qs = '?'.$qs;
        }

        return $this->getRequestedUrl($isAbsolute) . $qs;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentUrlWithParam($paramName, $paramValue, $isAbsolute = false)
    {

        $url = $this->getRequestedUrl($isAbsolute);

        $request = $this->dispatcher->getCurrentRequest();
        $queryString = $request->getQueryString();

        parse_str($queryString, $query);

        if (is_null($paramValue)) {
            unset($query[$paramName]);
        } else {
            $query[$paramName] = $paramValue;
        }

        return $query ? $url . '?' . http_build_query($query) : $url;
    }

    /**
     * Возвращает текущий URL без GET-параметров
     * @param bool $isAbsolute генерировать ли абсолютный URL
     * @return string
     */
    protected function getRequestedUrl($isAbsolute = false)
    {
        $request = $this->dispatcher->getCurrentRequest();

        $url = $request->getBaseUrl() . $request->getPathInfo();
        if ($isAbsolute) {
            $url = $request->getSchemeAndHttpHost() . $url;
        }

        return $url;
    }

    /**
     * Возвращает строку GET-параметров запроса
     * @return null|string
     */
    protected function getQueryString()
    {
        return $this->dispatcher->getCurrentRequest()->getQueryString();
    }

    /**
     * Возвращает URL админ-компонента относительно API-компонента.
     * @param AdminComponent $component
     * @return string
     */
    protected function getAdminRelativeComponentUrl(AdminComponent $component)
    {
        return str_replace(AdminComponent::PATH_SEPARATOR, '/', substr($component->getPath(), 17));
    }

}
 