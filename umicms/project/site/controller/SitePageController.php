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
use umicms\hmvc\controller\BaseSecureController;
use umicms\hmvc\dispatcher\CmsDispatcher;
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

        $view = parent::createView($templateName, $variables);
        $view->setXmlAttributes(['controller']);

        return $view;
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
