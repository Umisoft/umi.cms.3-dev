<?php
return array (
  'commonTemplateDirectory' => '~/project/template/common',
  'locales' => 
  array (
    'site' => 
    array (
      'ru-RU' => 
      array (
        'route' => 'default',
      ),
      'en-US' => 
      array (
        'route' => 'en-US',
      ),
    ),
  ),
  'routes' => 
  array (
    'en-US' => 
    array (
      'type' => 'ProjectHostRoute',
      'defaults' => 
      array (
        'prefix' => '/php/en',
        'locale' => 'en-US',
      ),
    ),
    'default' => 
    array (
      'type' => 'ProjectHostRoute',
      'defaults' => 
      array (
        'prefix' => '/php',
        'locale' => 'ru-RU',
      ),
    ),
  ),
  'defaultLocale' => 'ru-RU',
  'defaultPage' => '2099184c-013c-4653-8882-21c06d5e4e83',
  'defaultLayout' => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',
  'defaultTemplatingEngineType' => 'php',
  'defaultTemplateExtension' => 'phtml',
);