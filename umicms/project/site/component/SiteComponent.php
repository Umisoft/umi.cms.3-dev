<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\component;

use umi\config\exception\RuntimeException;
use umi\config\io\IConfigAliasResolverAware;
use umi\config\io\TConfigAliasResolverAware;
use umi\hmvc\dispatcher\IDispatchContext;
use umi\hmvc\model\IModelAware;
use umi\hmvc\view\IViewRenderer;
use umi\http\Request;
use umicms\hmvc\component\BaseCmsComponent;
use umicms\orm\object\ICmsPage;
use umicms\project\site\callstack\IPageCallStackAware;
use umicms\project\site\callstack\TPageCallStackAware;
use umicms\project\site\config\ISiteSettingsAware;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * Компонент сайта.
 */
class SiteComponent extends BaseCmsComponent implements IPageCallStackAware, ISiteSettingsAware, IConfigAliasResolverAware
{
    use TPageCallStackAware;
    use TSiteSettingsAware;
    use TConfigAliasResolverAware;

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

    /**
     * Возвращает конфигурацию щаблонизатора.
     * @return array
     */
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

        $templateDirectory = $this->getSiteTemplateDirectory();
        if ($templateDirectory) {
            try {
                list (, $localDirectory) = $this->getFilesByAlias($templateDirectory);
                $templateDirectory = $localDirectory;
            } catch(RuntimeException $e) {}
        }
        if ($templateDirectory) {
            $templateDirectory .= DIRECTORY_SEPARATOR;
        }

        $commonTemplateDirectory = $this->getSiteCommonTemplateDirectory();
        if ($commonTemplateDirectory) {
            try {
                list (, $localDirectory) = $this->getFilesByAlias($commonTemplateDirectory);
                $commonTemplateDirectory = $localDirectory;
            } catch(RuntimeException $e) {}
        }

        $directories = isset($config['directories']) ? (array) $config['directories'] : [];

        $config['directories'] = [];
        foreach ($directories as $directory) {
            $config['directories'][] = $templateDirectory . $directory;
        }
        if ($commonTemplateDirectory) {
            $config['directories'][] = $commonTemplateDirectory;
        }

        return $config;
    }

}