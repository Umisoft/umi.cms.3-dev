<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umi\i18n\TLocalesAware;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\controller\BaseController;
use umicms\i18n\CmsLocalesService;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер сетки сайта.
 */
class LayoutController extends BaseController implements ISiteSettingsAware, IPageCallStackAware, ILocalesAware
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
     * @var CmsLocalesService $traitLocalesService сервис для работы с локалями
     */
    private $localesService;

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
    public function setLocalesService(ILocalesService $localesService)
    {
        $this->localesService = $localesService;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $variables = [];

        $variables['title'] = $this->getMetaTitle();
        $variables['description'] = $this->getMetaDescription();
        $variables['keywords'] = $this->getMetaKeywords();
        $variables['locales'] = $this->getLocales();

        $variables['contents'] = $this->response->getContent();

        $response = $this->createViewResponse($this->getLayoutName(), $variables);

        $response->setStatusCode($this->response->getStatusCode());
        $response->headers->replace($this->response->headers->all());

        return $response;
    }

    /**
     * Возвращает список локалей сайта в формате [$localeId => $localeUrl, ...]
     * @return array
     */
    protected function getLocales()
    {
        $page = $this->hasCurrentPage() ? $this->getCurrentPage() : null;
        $urlManager = $this->getUrlManager();
        $localesService = $this->getLocalesService();

        $locales = [];
        foreach ($localesService->getSiteLocales() as $locale) {
            $url = $page ? $locale->getUrl() . '/' . $urlManager->getRawPageUrl($page) : $locale->getUrl();
            $localeId = $locale->getId();
            $locales[$localeId] = [
                'url' => $url,
                'current' => $localesService->getCurrentLocale() === $localeId
            ];
        }

        return $locales;
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

    /**
     * Возвращает сервис для работы с локалями
     * @throws RequiredDependencyException если сервис не был внедрен
     * @return CmsLocalesService
     */
    protected function getLocalesService()
    {
        if (!$this->localesService) {
            throw new RequiredDependencyException(sprintf(
                'Locales service is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->localesService;
    }

}


