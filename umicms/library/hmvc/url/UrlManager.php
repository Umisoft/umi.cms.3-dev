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

use umi\hmvc\component\IComponent;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;
use umicms\exception\RuntimeException;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\hmvc\component\admin\AdminComponent;
use umicms\project\module\structure\model\StructureModule;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\hmvc\component\site\BaseSitePageComponent;

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
     * @var string $schemeAndHttpHost схема и HTTP-хост проекта
     */
    protected $schemeAndHttpHost;
    /**
     * @var string $urlPrefix префикс URL проекта
     */
    protected $urlPrefix;
    /**
     * @var string $siteUrlPostfix постфикс для сайтовых URL проекта
     */
    protected $siteUrlPostfix;
    /**
     * @var string $adminUrlPrefix префикс URL для административной панели
     */
    protected $adminUrlPrefix;
    /**
     * @var string $restUrlPrefix префикс URL для REST-запросов
     */
    protected $restUrlPrefix;
    /**
     * @var array $systemPageUrls url системных страниц, по пути компонентов
     */
    protected $systemPageUrls = [];

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
    public function setUrlPrefix($urlPrefix)
    {
        $this->urlPrefix = $urlPrefix;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSiteUrlPostfix($urlPostfix)
    {
        $this->siteUrlPostfix = $urlPostfix;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchemeAndHttpHost($schemeAndHttpHost)
    {
        $this->schemeAndHttpHost = $schemeAndHttpHost;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRestUrlPrefix($restUrlPrefix)
    {
        $this->restUrlPrefix = $restUrlPrefix;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdminUrlPrefix($adminUrlPrefix)
    {
        $this->adminUrlPrefix = $adminUrlPrefix;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProjectUrl($isAbsolute = false)
    {
        if ($isAbsolute) {
            return $this->getSchemeAndHttpHost() . $this->urlPrefix;
        }

        return $this->urlPrefix ?: '/';
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteUrlPostfix()
    {
        return $this->siteUrlPostfix;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemeAndHttpHost()
    {
        return $this->schemeAndHttpHost;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseRestUrl()
    {
        return $this->getBaseAdminUrl() . $this->restUrlPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAdminUrl()
    {
        return $this->urlPrefix . $this->adminUrlPrefix;
    }

    public function getRawPageUrl(ICmsPage $page, $handler = ICmsCollection::HANDLER_SITE)
    {
        if ($page instanceof StructureElement) {
            return $page->getURL();
        }

        /**
         * @var ICmsCollection $collection
         */
        $collection = $page->getCollection();
        $handlerPath = $collection->getHandlerPath($handler);

        $component = $this->dispatcher->getComponentByPath(
            CmsDispatcher::SITE_COMPONENT_PATH . IComponent::PATH_SEPARATOR . $handlerPath
        );
        if (!$component instanceof BaseSitePageComponent) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot get url for page with GUID "{guid}". Component "{path}" should be instance of "{class}".',
                    [
                        'guid' => $page->getGUID(),
                        'path' => $component->getPath(),
                        'class' => 'umicms\hmvc\component\site\BaseSitePageComponent'
                    ]
                )
            );
        }

        return $this->getRawSystemPageUrl($handlerPath) . $component->getPageUri($page);
    }

    /**
     * {@inheritdoc}
     */
    public function getRawSystemPageUrl($componentPath)
    {
        if (!isset($this->systemPageUrls[$componentPath])) {
            $this->systemPageUrls[$componentPath] = $this->structureApi
                ->element()
                ->getSystemPageByComponentPath($componentPath)
                ->getURL();
        }

        return $this->systemPageUrls[$componentPath];
    }

    /**
     * {@inheritdoc}
     */
    public function getSitePageUrl(ICmsPage $page, $isAbsolute = false, $handler = ICmsCollection::HANDLER_SITE)
    {
        $pageUrl = $isAbsolute ? $this->schemeAndHttpHost : '';
        $pageUrl .= $this->urlPrefix . '/';
        $pageUrl .= $this->getRawPageUrl($page, $handler);

        if ($this->siteUrlPostfix) {
            $pageUrl .= '.' . $this->siteUrlPostfix;
        }

        return $pageUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getSystemPageUrl($componentPath, $isAbsolute = false)
    {
        $pageUrl = $isAbsolute ? $this->schemeAndHttpHost : '';
        $pageUrl .= $this->urlPrefix . '/';
        $pageUrl .= $this->getRawSystemPageUrl($componentPath);

        if ($this->siteUrlPostfix) {
            $pageUrl .= '.' . $this->siteUrlPostfix;
        }

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
        if (!$collection->hasForm(ICmsCollection::FORM_EDIT, $object->getTypeName())) {
            throw new RuntimeException($this->translate(
                'Collection "{collection}" does not have edit form for type "{type}".',
                ['collection' => $collection->getName(), 'type' => $object->getTypeName()]
            ));
        }

        $editLink = $isAbsolute ? $this->schemeAndHttpHost : '';
        $editLink .= $this->getBaseAdminUrl();
        $editLink .= '/' . str_replace('.', '/', $collection->getHandlerPath('admin'));
        $editLink .= '/' . $object->getId() . '/editForm';

        return $editLink;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollectionResourceUrl(ICmsCollection $collection, ICmsObject $object = null)
    {
        $collectionResourceUrl = $this->getBaseRestUrl();
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
        $domainUrl = $isAbsolute ? $this->schemeAndHttpHost : '';

        return $domainUrl . $this->getBaseAdminUrl() . $this->getAdminRelativeComponentUrl($component);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentResourceUrl(AdminComponent $component)
    {
        return $this->getBaseRestUrl() . $this->getAdminRelativeComponentUrl($component);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentActionResourceUrl(AdminComponent $component, $actionName, array $params = [])
    {
        $actionUrl = $this->getAdminComponentResourceUrl($component);
        $actionUrl .= $component->getRouter()->assemble('action', ['action' => $actionName]);

        if ($params) {
            $actionUrl .= '?' . urldecode(http_build_query($params));
        }

        return $actionUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentUrl($isAbsolute = false)
    {
        if (null !== ($qs = $this->getQueryString())) {
            $qs = '?' . $qs;
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
     * Возвращает URL админ-компонента относительно REST-компонента.
     * @param AdminComponent $component
     * @return string
     */
    protected function getAdminRelativeComponentUrl(AdminComponent $component)
    {
        return str_replace(AdminComponent::PATH_SEPARATOR, '/', substr($component->getPath(), 18));
    }

}
 