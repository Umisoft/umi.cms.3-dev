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
      'guid' => '4a9f0130-5e56-49f7-b873-c99807110c81',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
        1 => '$2a$09$53c3e37f8001d1.199402urQjZ2FU3GgR3GiLnc/w8IP3qZvbQaOu',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3e37f8001d1.19940209$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Администратор',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
        1 => '$2a$09$53c3e37f47f7e7.111657uHaEqW4TpDtuSZhP9f0EE/8fWvfAPdJO',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3e37f47f7e7.11165710$',
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
      'guid' => 'ad4a3c78-e06b-4794-8cc2-7656ce6ff00b',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
        1 => '$2a$09$53c3e37fab4814.156285uKwmVIBIMBpBy2UYpSgLVsyoh4iguioK',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3e37fab4814.15628594$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Зарегистрированный пользователь',
      ),
    ),
  ),
);