<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project;

use umi\config\entity\IConfig;
use umicms\exception\RequiredDependencyException;

/**
 * Трейт для работы с настройками проекта
 */
trait TProjectSettingsAware
{
    /**
     * @var IConfig $traitSiteSettings
     */
    private $traitProjectSettings;

    /**
     * @see IProjectSettingsAware::setProjectSettings()
     */
    public function setProjectSettings(IConfig $config)
    {
        $this->traitProjectSettings = $config;
    }

    /**
     * Возвращает настройки проекта.
     * @throws RequiredDependencyException если настройки не были установлены
     * @return IConfig
     */
    protected function getProjectSettings()
    {
        if (!$this->traitProjectSettings) {
            throw new RequiredDependencyException(sprintf(
                'Project settings is not injected in class "%s".',
                get_class($this)
            ));
        }

        return $this->traitProjectSettings;
    }

    /**
     * Возвращает GUID шаблона сайта по умолчанию.
     * @return string
     */
    protected function getSiteDefaultLayoutGuid()
    {
        return $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID);
    }

    /**
     * Возвращает title страниц по умолчанию.
     * @return string
     */
    protected function getSiteDefaultTitle()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_TITLE);
    }

    /**
     * Возвращает префикс для title страниц.
     * @return string
     */
    protected function getSiteTitlePrefix()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_TITLE_PREFIX);
    }

    /**
     * Возвращает keywords страниц по умолчанию.
     * @return string
     */
    protected function getSiteDefaultKeywords()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS);
    }

    /**
     * Возвращает description страниц по умолчанию.
     * @return string
     */
    protected function getSiteDefaultDescription()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION);
    }

    /**
     * Возвращает тип шаблонизатора по умолчанию
     * @return string
     */
    protected function getSiteDefaultTemplateEngineType()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE);
    }

    /**
     * Возвращает расширение файлов шаблонов по умолчанию
     * @return string
     */
    protected function getSiteDefaultTemplateExtension()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION);
    }

    /**
     * Возвращает путь директории с шаблонами сайта
     * @return string
     */
    protected function getSiteTemplateDirectory()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY);
    }

    /**
     * Возвращает путь директории с общими шаблонами сайта
     * @return string
     */
    protected function getSiteCommonTemplateDirectory()
    {
        return (string) $this->getProjectSettings()->get(IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY);
    }
}
