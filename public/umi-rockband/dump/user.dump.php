<?php
/**
 * Collection "user" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'user',
      'type' => 'registered',
      'guid' => '2e8f1860-b98d-4fe8-8f7a-5627c42ed8af',
      'displayName' => 'Зарегистрированный пользователь',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Зарегистрированный пользователь',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
      ),
      'owner' => 
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
      'active#ru-RU' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'locked' => 
      array (
        0 => 'boolean',
        1 => false,
      ),
      'login' => 
      array (
        0 => 'string',
        1 => 'demo',
      ),
      'email' => 
      array (
        0 => 'string',
        1 => 'demo@umisoft.ru',
      ),
      'password' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c929046d8.836376upxZjfLEZwn9ZbSkC2DfY2pox2dBuWii',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c929046d8.83637617$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Зарегистрированный пользователь',
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'user',
      'type' => 'guest',
      'guid' => '552802d2-278c-46c2-9525-cd464bbed63e',
      'displayName' => 'Гость',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Guest',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
      ),
      'owner' => 
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
      'active#ru-RU' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'locked' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
    ),
  ),
  2 => 
  array (
    'meta' => 
    array (
      'collection' => 'user',
      'type' => 'registered.supervisor',
      'guid' => '68347a1d-c6ea-49c0-9ec3-b7406e42b01e',
      'displayName' => 'Супервайзер',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Supervisor',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
      ),
      'owner' => 
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
      'active#ru-RU' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'locked' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'login' => 
      array (
        0 => 'string',
        1 => 'sv',
      ),
      'email' => 
      array (
        0 => 'string',
        1 => 'sv@umisoft.ru',
      ),
      'password' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c92752a37.234243uE5tGY1qDrOtl6qiqjkKkc9rpJ9dhPty',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c92752a37.23424375$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Супервайзер',
      ),
    ),
  ),
  3 => 
  array (
    'meta' => 
    array (
      'collection' => 'user',
      'type' => 'registered',
      'guid' => 'b2fab3a5-0c09-46fe-b87e-06808a10608a',
      'displayName' => 'Администратор',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Администратор',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Administrator',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
      ),
      'owner' => 
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
      'active#ru-RU' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => true,
      ),
      'locked' => 
      array (
        0 => 'boolean',
        1 => false,
      ),
      'login' => 
      array (
        0 => 'string',
        1 => 'admin',
      ),
      'email' => 
      array (
        0 => 'string',
        1 => 'admin@umisoft.ru',
      ),
      'password' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c9283b198.211266ujFnS.XkOhGvCmuWzgB7q9nAw9jSqxK2',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53bc1c9283b198.21126656$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Администратор',
      ),
    ),
  ),
);