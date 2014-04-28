<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umicms\hmvc\controller\BaseController;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер сетки сайта.
 */
class LayoutController extends BaseController implements ISiteSettingsAware, IPageCallStackAware
{

    use TSiteSettingsAware;
    use TPageCallStackAware;

    /**
     * @var Response $response содержимое страницы
     */
    protected $response;
    /**
     * @var StructureModule $structure
     */
    protected $structure;

    /**
     * Конструктор.
     * @param Response $response
     * @param StructureModule $structure
     */
    public function __construct(Response $response, StructureModule $structure)
    {
        $this->response = $response;
        $this->structure = $structure;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $variables = [];

        if ($this->hasCurrentPage()) {
            $variables['title'] = $this->getMetaTitle();
            $variables['description'] = $this->getMetaDescription();
            $variables['keywords'] = $this->getMetaKeywords();
           // $variables['locales'] =
        }

        $variables['contents'] = $this->response->getContent();

        $response = $this->createViewResponse($this->getLayoutName(), $variables);

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }

    /**
     * Вычисляет meta-title.
     * @return string
     */
    protected function getMetaTitle()
    {
        $titlePrefix = $this->getSiteTitlePrefix();

        foreach ($this->getPageCallStack() as $page) {
            if ($page->metaTitle) {
                return $titlePrefix . $page->metaTitle;
            }
        }

        return $titlePrefix . $this->getSiteDefaultTitle();
    }

    /**
     * Вычисляет meta-keywords.
     * @return string
     */
    protected function getMetaKeywords()
    {
        foreach ($this->getPageCallStack() as $page) {
            if ($page->metaKeywords) {
                return $page->metaKeywords;
            }
        }

        return $this->getSiteDefaultKeywords();
    }

    /**
     * Вычисляет meta-description.
     * @return string
     */
    protected function getMetaDescription()
    {
        foreach ($this->getPageCallStack() as $page) {
            if ($page->metaDescription) {
                return $page->metaDescription;
            }
        }

        return $this->getSiteDefaultDescription();
    }

    /**
     * Вычисляет имя шаблона-сетки.
     * @return string
     */
    protected function getLayoutName()
    {
        foreach ($this->getPageCallStack() as $page) {
            if ($page->layout) {
                return $page->layout->fileName;
            }
        }

        return $this->structure->layout()->getDefaultLayout()->fileName;
    }

}


