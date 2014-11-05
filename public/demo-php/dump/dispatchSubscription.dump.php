<?php
/**
 * Collection "dispatchSubscription" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'dispatchSubscription',
      'type' => 'base',
      'guid' => '65753670-4b42-4a68-9ff7-af9a89e3b023',
      'displayName' => 'Farit:Hi all:mail@faritka.ru:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Farit:Hi all:mail@faritka.ru:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-28 11:01:33";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
            'guid' => 'e62476f5-0c32-474f-9b4b-6c47010352d2',
            'displayName' => 'Farit',
          ),
        ),
      ),
      'dispatch' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'dispatch',
            'type' => 'base',
            'guid' => '48d9a3af-5a47-4340-a35b-5fae2cb392d5',
            'displayName' => 'Hi all',
          ),
        ),
      ),
      'subscriber' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'dispatchSubscriber',
            'type' => 'base',
            'guid' => '400f3afa-89d6-4e5f-b242-09d6c8b051c9',
            'displayName' => 'mail@faritka.ru',
          ),
        ),
      ),
      'token' => 
      array (
        0 => 'string',
        1 => 'f1004e36-b3de-444a-a35c-9101856297e6',
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'dispatchSubscription',
      'type' => 'base',
      'guid' => '6f30be51-de1b-4e3e-980f-48f1357e7ee0',
      'displayName' => 'Супервайзер:Hello world:sv@umisoft.ru:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Hello world:sv@umisoft.ru:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-29 16:50:50";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'dispatch' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'dispatch',
            'type' => 'base',
            'guid' => '81e04e35-d079-4515-9aa0-4985db30363b',
            'displayName' => 'Hello world',
          ),
        ),
      ),
      'subscriber' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'dispatchSubscriber',
            'type' => 'base',
            'guid' => '479e354f-4614-4c98-9230-4b6305c91875',
            'displayName' => 'sv@umisoft.ru',
          ),
        ),
      ),
      'token' => 
      array (
        0 => 'string',
        1 => '1ad51be9-51d4-4a24-999a-159788ea047a',
      ),
    ),
  ),
);