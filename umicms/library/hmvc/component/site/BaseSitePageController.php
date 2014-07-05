<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\hmvc\component\site;

use umi\hmvc\component\IComponent;
use umicms\hmvc\component\BaseCmsController;
use umicms\hmvc\dispatcher\CmsDispatcher;
use umicms\orm\object\ICmsPage;
use umicms\project\module\structure\model\object\SystemPage;
use umicms\project\module\structure\model\StructureModule;
use umicms\hmvc\callstack\IPageCallStackAware;
use umicms\hmvc\callstack\TPageCallStackAware;
use umicms\serialization\ISerializer;
use umicms\serialization\xml\BaseSerializer;

/**
 * Базовый контроллер для сайта
 */
abstract class BaseSitePageController extends BaseCmsController implements IPageCallStackAware
{
    use TPageCallStackAware;

    /**
     * {@inheritdoc}
     */
    protected function createView($templateName, array $variables = []) {
        $variables['controller'] = $this->getShortPath();
        $variables['breadcrumbs'] = $this->getBreadcrumbs();

        $view = parent::createView($templateName, $variables);
        $view->addSerializerConfigurator(
            function(ISerializer $serializer)
            {
                if ($serializer instanceof BaseSerializer) {
                    $serializer->setAttributes(['controller']);
                }
            }
        );

        return $view;
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

        /**
         * @var StructureModule $structureModule
         */
        $structureModule = $this->getModule(StructureModule::className());

        $defaultPage = $structureModule->getDefaultPage();
        if ($defaultPage !== $callStack->bottom()) {
            $breadcrumbs[] = $this->getBreadcrumb($defaultPage);
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        return $breadcrumbs;
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
