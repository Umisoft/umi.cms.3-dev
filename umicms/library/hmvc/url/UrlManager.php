<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\url;

use umicms\hmvc\dispatcher\Dispatcher;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;
use umicms\project\admin\component\AdminComponent;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\module\structure\api\object\StructureElement;
use umicms\project\site\component\SiteComponent;

/**
 * URL-менеджер.
 */
class UrlManager implements IUrlManager
{
    /**
     * @var Dispatcher $dispatcher диспетчер компонентов
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
     * @var string $baseAdminUrl базовый URL для административной панели
     */
    protected $baseAdminUrl;
    /**
     * @var string $domainUrl URL домена проекта
     */
    protected $domainUrl;

    /**
     * Конструктор.
     * @param Dispatcher $dispatcher диспетчер компонентов
     * @param StructureModule $structureApi
     */
    public function __construct(Dispatcher $dispatcher, StructureModule $structureApi)
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
    public function getBaseAdminUrl()
    {
        return $this->baseAdminUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getSitePageUrl(ICmsPage $page, $isAbsolute = false)
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
        $handler = $collection->getHandlerPath('site');

        /**
         * @var SiteComponent $component
         */
        $component = $this->dispatcher->getSiteComponentByPath($handler);

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
    public function getAdminComponentUrl(AdminComponent $component, $isAbsolute = false)
    {
        $domainUrl = $isAbsolute ? $this->domainUrl : '';

        return $domainUrl . $this->baseAdminUrl . $this->getRelativeComponentUrl($component);
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
    public function getAdminComponentResourceUrl(AdminComponent $component)
    {
        return $this->baseRestUrl . $this->getRelativeComponentUrl($component);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminComponentActionResourceUrl(AdminComponent $component, $actionName)
    {
        $actionUrl = $this->getAdminComponentResourceUrl($component);
        $actionUrl .= $component->getRouter()->assemble('action', ['action' => $actionName]);

        return $actionUrl;
    }

    /**
     * Возвращает URL компонента относительно API-компонента.
     * @param AdminComponent $component
     * @return string
     */
    protected function getRelativeComponentUrl(AdminComponent $component)
    {
        return str_replace(AdminComponent::PATH_SEPARATOR, '/', substr($component->getPath(), 17));
    }
}
 