<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\controller;

use umi\hmvc\component\IComponent;
use umi\orm\object\IObject;
use umicms\hmvc\controller\BaseSecureController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\api\object\SystemPage;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;

/**
 * Базовый контроллер для сайта
 */
abstract class SitePageController extends BaseSecureController implements IPageCallStackAware
{
    use TPageCallStackAware;

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
            $breadcrumbs[] = $this->getBreabcrumbs($page);
            if ($page == $callStack->top()) {
                $ancestry = $this->getNavigationAncestry($page);

                $navigationAncestry = [];
                foreach ($ancestry as $ancestryPage) {
                    $navigationAncestry[] = $this->getBreabcrumbs($ancestryPage);
                }

                $navigationAncestry = array_reverse($navigationAncestry);
                $breadcrumbs = array_merge($breadcrumbs, $navigationAncestry);
            }
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
    private function getBreabcrumbs(ICmsPage $page)
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
