<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\controller;

use umi\http\Response;
use umi\i18n\ILocalesAware;
use umi\i18n\ILocalesService;
use umi\i18n\TLocalesAware;
use umicms\exception\RequiredDependencyException;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\view\CmsLayoutView;
use umicms\i18n\CmsLocalesService;
use umicms\project\module\structure\model\StructureModule;
use umicms\hmvc\callstack\IPageCallStackAware;
use umicms\hmvc\callstack\TPageCallStackAware;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Контроллер сетки сайта.
 */
class LayoutController extends BaseCmsController implements ISiteSettingsAware, IPageCallStackAware, ILocalesAware
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
        $urlManager = $this->getUrlManager();
        $currentUrl = substr($urlManager->getCurrentUrl(true), strlen($urlManager->getProjectUrl(true)));

        $localesService = $this->getLocalesService();

        $locales = [];
        foreach ($localesService->getSiteLocales() as $locale) {

            $localeId = $locale->getId();
            $isCurrent = $localesService->getCurrentLocale() === $localeId;
            $url = $locale->getUrl() . $currentUrl;
            if (!$isCurrent && $urlManager->getSiteUrlPostfix()) {
                $sitePostfix = $urlManager->getSiteUrlPostfix();
                $url = substr($url, 0, -(strlen($sitePostfix) + 1));
            }

            $locales[$localeId] = [
                'url' => $url,
                'current' => $isCurrent
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

    /**
     * {@inheritdoc}
     */
    protected function createView($templateName, array $variables = [])
    {
        return new CmsLayoutView($this, $this->getContext(), $templateName, $variables);
    }

}


