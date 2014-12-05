<?php
return array (
  'commonTemplateDirectory' => '~/project/template/common',
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
  'defaultPage' => '002675ac-9e29-4675-abf7-aa0f93ff9a8c',
  'defaultLayout' => 'd6cb8b38-7e2d-4b36-8d15-9fe8947d66c7',
  'defaultTemplatingEngineType' => 'php',
  'defaultTemplateExtension' => 'phtml',
);