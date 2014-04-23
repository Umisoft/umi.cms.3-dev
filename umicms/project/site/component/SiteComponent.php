<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\site\component;

use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\model\IModelAware;
use umi\hmvc\view\IViewRenderer;
use umi\http\Request;
use umicms\hmvc\component\BaseComponent;
use umicms\orm\object\ICmsPage;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Компонент сайта.
 */
class SiteComponent extends BaseComponent implements IPageCallStackAware, ISiteSettingsAware
{
    use TPageCallStackAware;
    use TSiteSettingsAware;

    /**
     * @var IViewRenderer $viewRenderer рендерер шаблонов
     */
    private $viewRenderer;

    /**
     * Имя параметра маршрута для определения элемента структуры.
     */
    const MATCH_STRUCTURE_ELEMENT = 'element';

    /**
     * {@inheritdoc}
     */
    public function onDispatchRequest(IDispatchContext $context, Request $request)
    {
        $element = null;
        if (isset($context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT])) {
            $element = $context->getRouteParams()[self::MATCH_STRUCTURE_ELEMENT];

            if ($element instanceof ICmsPage) {
                $this->pushCurrentPage($element);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRenderer()
    {
        if (!$this->viewRenderer) {

            $config = $this->getViewRendererConfig();

            $viewRenderer = $this->createMvcViewRenderer($config);

            if ($viewRenderer instanceof IModelAware) {
                $viewRenderer->setModelFactory($this->getModelsFactory());
            }

            return $this->viewRenderer = $viewRenderer;
        }

        return $this->viewRenderer;
    }

    protected function getViewRendererConfig()
    {
        $config = isset($this->options[self::OPTION_VIEW]) ? $this->options[self::OPTION_VIEW] : [];
        $config = $this->configToArray($config, true);

        if (!isset($config['type'])) {
            $config['type'] = $this->getSiteDefaultTemplateEngineType();
        }

        if (!isset($config['extension'])) {
            $config['extension'] = $this->getSiteDefaultTemplateExtension();
        }

        if ($commonDirectory = $this->getSiteCommonTemplateDirectory()) {
            $directories = isset($config['directories']) ? (array) $config['directories'] : [];
            $directories[] = $commonDirectory;

            $config['directories'] = $directories;
        }

        return $config;
    }

}