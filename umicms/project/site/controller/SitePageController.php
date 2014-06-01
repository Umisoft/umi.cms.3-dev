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

use umi\hmvc\component\IComponent;
use umicms\hmvc\controller\BaseSecureController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\api\object\SystemPage;
use umicms\project\module\structure\api\StructureModule;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * Базовый контроллер для сайта
 */
abstract class SitePageController extends BaseSecureController implements IPageCallStackAware, IModuleAware
{
    use TPageCallStackAware;
    use TModuleAware;

    /**
     * {@inheritdoc}
     */
    protected function createView($templateName, array $variables = []) {
        $variables['controller'] = $this->getShortPath();
        $variables['breadcrumbs'] = $this->getBreadcrumbs();

        $view = parent::createView($templateName, $variables);
        $view->setXmlAttributes(['controller']);

        return $view;
    }

    /**
     * Формирует хлебные крошки.
     * @return array
     */
    private function getBreadcrumbs()
    {
        $callStack = $this->getPageCallStack();

        $breadcrumbs = [];
        foreach ($callStack as $page) {
            if ($page instanceof SystemPage && $page->skipInBreadcrumbs) {
                continue;
            }
            $breadcrumbs[] = $this->getBreadcrumb($page);
            if ($page === $callStack->top()) {
                $ancestry = $this->getNavigationAncestry($page);

                $navigationAncestry = [];
                foreach ($ancestry as $ancestryPage) {
                    $navigationAncestry[] = $this->getBreadcrumb($ancestryPage);
                }

                $navigationAncestry = array_reverse($navigationAncestry);
                $breadcrumbs = array_merge($breadcrumbs, $navigationAncestry);
            }
        }

        /** @var StructureModule $structureModule */
        $structureModule = $this->getModule(StructureModule::className());

        $defaultPage = $structureModule->getDefaultPage();
        if ($defaultPage !== $callStack->bottom()) {
            $breadcrumbs[] = $this->getBreadcrumb($defaultPage);
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        return $breadcrumbs;
    }

    /**
     * Возвращает массив элементов навигации до текущего элемента.
     * @param ICmsPage $page
     * @return ICmsPage[]
     */
    protected function getNavigationAncestry(ICmsPage $page)
    {
        return [];
    }

    /**
     * Возвращает массив представляющий хлебную крошку.
     * @param ICmsPage $page
     * @return array
     */
    private function getBreadcrumb(ICmsPage $page)
    {
        return [
            'url' => $page->getPageUrl(),
            'displayName' => $page->displayName
        ];
    }

    /**
     * Возвращает короткий путь контроллера, относительно приложения сайта
     * @return string
     */
    private function getShortPath()
    {
        $relativePath = substr($this->getComponent()->getPath(), strlen(CmsDispatcher::SITE_COMPONENT_PATH) + 1);
        $relativePath .= IComponent::PATH_SEPARATOR . $this->getName();

        return $relativePath;
    }
}
