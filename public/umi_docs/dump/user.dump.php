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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-17 19:23:45";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
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
        1 => false,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => false,
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
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-17 19:23:45";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}',
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
        1 => false,
      ),
      'active#en-US' => 
      array (
        0 => 'boolean',
        1 => false,
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
        1 => '$2a$09$53c7ea76864a35.973013uSejaGDFaZQYxtjuwiLCzQOT1iYLoHO.',
      ),
      'passwordSalt' => 
      array (
        0 => 'string',
        1 => '$2a$09$53c7ea76864a35.97301307$',
      ),
      'firstName' => 
      array (
        0 => 'string',
        1 => 'Супервайзер',
      ),
    ),
  ),
);