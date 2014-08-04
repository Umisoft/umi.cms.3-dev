<?php
/**
 * Collection "userUserGroup" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '45b288bf-83ea-4792-bf35-3ea8ef8ab631',
      'displayName' => 'Супервайзер:Гость:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Гость:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-17 19:23:45";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'guest',
            'guid' => '552802d2-278c-46c2-9525-cd464bbed63e',
            'displayName' => 'Гость',
          ),
        ),
      ),
      'userGroup' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'userGroup',
            'type' => 'base',
            'guid' => 'bedcbbac-7dd1-4b60-979a-f7d944ecb08a',
            'displayName' => 'Посетители',
          ),
        ),
      ),
    ),
  ),
);