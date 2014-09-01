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

/**
 * Интерфейс для использования настроек проекта.
 */
interface IProjectSettingsAware
{
    /**
     * Имя настройки для задания guid главной страницы
     */
    const SETTING_DEFAULT_PAGE_GUID = 'defaultPage';
    /**
     * Имя настройки для задания guid шаблона по умолчанию
     */
    const SETTING_DEFAULT_LAYOUT_GUID = 'defaultLayout';
    /**
     * Имя настройки для задания title страниц по умолчанию
     */
    const SETTING_DEFAULT_TITLE = 'defaultMetaTitle';
    /**
     * Имя настройки для задания префикса title страниц
     */
    const SETTING_TITLE_PREFIX = 'metaTitlePrefix';
    /**
     * Имя настройки для задания keywords страниц по умолчанию
     */
    const SETTING_DEFAULT_KEYWORDS = 'defaultMetaKeywords';
    /**
     * Имя настройки для задания description страниц по умолчанию
     */
    const SETTING_DEFAULT_DESCRIPTION = 'defaultMetaDescription';
    /**
     * Имя настройки для задания шаблонизатора по умолчанию
     */
    const SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE = 'defaultTemplatingEngineType';
    /**
     * Имя настройки для задания расширения файлов с шаблонами по умолчанию
     */
    const SETTING_DEFAULT_TEMPLATE_EXTENSION = 'defaultTemplateExtension';
    /**
     * Имя настройки для задания директории общих шаблонов
     */
    const SETTING_COMMON_TEMPLATE_DIRECTORY = 'commonTemplateDirectory';
    /**
     * Имя настройки для задания директории шаблонов
     */
    const SETTING_TEMPLATE_DIRECTORY = 'templateDirectory';

    /**
     * Устанавливает настройки проекта.
     * @param IConfig $config
     */
    public function setProjectSettings(IConfig $config);
}
