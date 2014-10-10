<?php

use umicms\project\IProjectSettingsAware;

return [
    'en-US' => [
        'component:site:displayName' => 'Site settings',

        IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION => 'Default meta description tag',
        IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS => 'Default meta keywords tag',
        IProjectSettingsAware::SETTING_DEFAULT_TITLE => 'Default title tag',
        IProjectSettingsAware::SETTING_TITLE_PREFIX => 'Default title prefix',

        IProjectSettingsAware::SETTING_DEFAULT_PAGE_GUID => 'Index page',
        IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID => 'Default layout',

        IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY => 'Template directory',
        IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY => 'Common template directory',
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION => 'Default template extension',
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => 'Default templating engine type',

        'role:commonExecutor:displayName' => 'Common',
        'role:templatingExecutor:displayName' => 'Templating',
        'role:slugifyExecutor:displayName' => 'Slug formation',
        'role:licenseExecutor:displayName' => 'License',
        'role:mailExecutor:displayName' => 'Mail',
    ],

    'ru-RU' => [

        'component:site:displayName' => 'Настройки сайта',

        IProjectSettingsAware::SETTING_DEFAULT_DESCRIPTION => 'Метатег description по умолчанию',
        IProjectSettingsAware::SETTING_DEFAULT_KEYWORDS => 'Метатег keywords по умолчанию',
        IProjectSettingsAware::SETTING_DEFAULT_TITLE => 'Метатег title по умолчанию',
        IProjectSettingsAware::SETTING_TITLE_PREFIX => 'Префикс для метатега title',

        IProjectSettingsAware::SETTING_DEFAULT_PAGE_GUID => 'Главная страница',
        IProjectSettingsAware::SETTING_DEFAULT_LAYOUT_GUID => 'Шаблон страниц по умолчанию',

        IProjectSettingsAware::SETTING_TEMPLATE_DIRECTORY => 'Директория с шаблонами',
        IProjectSettingsAware::SETTING_COMMON_TEMPLATE_DIRECTORY => 'Директория с общими шаблонами',
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATE_EXTENSION => 'Расширение файлов шаблонов по умолчанию',
        IProjectSettingsAware::SETTING_DEFAULT_TEMPLATING_ENGINE_TYPE => 'Тип шаблонизатора по умолчанию',

        'role:commonExecutor:displayName' => 'Общие',
        'role:templatingExecutor:displayName' => 'Шаблонизация',
        'role:slugifyExecutor:displayName' => 'Формирование псевдостатического адреса',
        'role:licenseExecutor:displayName' => 'Лицензирование',
        'role:mailExecutor:displayName' => 'Почта',
    ]
];
 