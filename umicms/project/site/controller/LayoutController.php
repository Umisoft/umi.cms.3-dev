<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\api\StructureApi;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер сетки сайта.
 */
class LayoutController extends SitePageController implements ISiteSettingsAware
{

    use TSiteSettingsAware;

    /**
     * @var Response $response содержимое страницы
     */
    protected $response;
    /**
     * @var StructureApi $structureApi
     */
    protected $structureApi;

    /**
     * Конструктор.
     * @param Response $response
     * @param StructureApi $structureApi
     */
    public function __construct(Response $response, StructureApi $structureApi)
    {
        $this->response = $response;
        $this->structureApi = $structureApi;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $response = $this->createViewResponse(
            $this->getLayoutName(),
            [
                'title' => $this->getMetaTitle(),
                'description' => $this->getMetaDescription(),
                'keywords' => $this->getMetaKeywords(),
                'contents' => $this->response->getContent()
            ]
        );

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }


    protected function getMetaTitle()
    {
        $titlePrefix = $this->getSiteTitlePrefix();

        if ($this->hasCurrentPage()) {
            /**
             * @var ICmsPage $page
             */
            foreach ($this->getPageCallStack() as $page) {
                if ($page->metaTitle) {
                    return $titlePrefix . $page->metaTitle;
                }
            }
        }

        return $titlePrefix . $this->getSiteDefaultTitle();
    }

    protected function getMetaKeywords()
    {
        if ($this->hasCurrentPage()) {
            /**
             * @var ICmsPage $page
             */
            foreach ($this->getPageCallStack() as $page) {
                if ($page->metaKeywords) {
                    return $page->metaKeywords;
                }
            }
        }

        return $this->getSiteDefaultKeywords();
    }

    protected function getMetaDescription()
    {
        if ($this->hasCurrentPage()) {
            /**
             * @var ICmsPage $page
             */
            foreach ($this->getPageCallStack() as $page) {
                if ($page->metaDescription) {
                    return $page->metaDescription;
                }
            }
        }

        return $this->getSiteDefaultDescription();
    }

    protected function getLayoutName()
    {
        if ($this->hasCurrentPage()) {
            /**
             * @var ICmsPage $page
             */
            foreach ($this->getPageCallStack() as $page) {
                if ($page->layout) {
                    return $page->layout->fileName;
                }
            }
        }

        return $this->structureApi->layout()->getDefaultLayout()->fileName;
    }

}


