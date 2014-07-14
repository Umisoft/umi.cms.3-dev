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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 15:45:21";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'user',
      'type' => 'registered',
      'guid' => '5ff110ff-a8a6-42f3-aa49-9ea15b1cc935',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 15:45:21";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
        1 => '$2a$09$53c3deefdf8525.915893u1Lp.fsYLMexZP68dQZtjU0AKSOMzjU6',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3deefdf8525.91589323$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Зарегистрированный пользователь',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 15:45:21";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
        1 => '$2a$09$53c3deef579097.422420uB5rOEa8gZ0qAyBj2sx6FjJqgqgxg2ni',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3deef579097.42242052$',
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
      'guid' => 'f1f39974-b34c-4a0d-8ae9-e2503f564b9a',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 15:45:21";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
        1 => '$2a$09$53c3deefb4feb0.713054ucCUkolzpVBvIRPgYaFylTwis7KxXSSm',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c3deefb4feb0.71305410$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Администратор',
      ),
    ),
  ),
);