<?php
/**
 * Collection "dispatchRelease" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'dispatchRelease',
      'type' => 'base',
      'guid' => 'ddd10c22-3832-4c38-86f2-8a26460e2e48',
      'displayName' => 'Тестовое письмо',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Тестовое письмо',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-11-05 18:49:04";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'updated' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-11-05 19:16:55";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'editor' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered.supervisor',
            'guid' => '68347a1d-c6ea-49c0-9ec3-b7406e42b01e',
            'displayName' => 'Супервайзер',
          ),
        ),
      ),
      'subject' => 
      array (
        0 => 'string',
        1 => 'Это тест Controller',
      ),
      'header' => 
      array (
        0 => 'string',
        1 => 'Тестируем контроллер выпуска рассылки',
      ),
      'message' => 
      array (
        0 => 'string',
        1 => '<p>Здравствуй {subscriber}! Я тестирую контроллер выпуски рассылок, я&nbsp;устал и очень зол, т.к.&nbsp;не работает интернет, вот твоя почта: {email}</p>
',
      ),
      'template' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'dispatchTemplate',
            'type' => 'base',
            'guid' => 'cc0a4757-9d43-4e54-93d2-eb2d2d17251a',
            'displayName' => 'Тестовый шаблон',
          ),
        ),
      ),
    ),
  ),
);