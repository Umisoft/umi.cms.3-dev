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
      'guid' => '04cac8ff-8b45-41f1-8f11-900a5abb723c',
      'displayName' => 'Гость:mail@faritka.ru:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:mail@faritka.ru:Зaрегистрированные пользователи:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-21 19:51:31";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
            'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
            'displayName' => 'Зaрегистрированные пользователи',
          ),
        ),
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '116343c6-7f63-4ce8-a1d2-599187858efa',
      'displayName' => 'Посетитель:Посетитель:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Посетитель:Посетитель:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-14 13:22:04";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'visitor',
            'guid' => '8bfb5927-62ca-4bc9-b677-d32a5d093714',
            'displayName' => 'Посетитель',
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
            'type' => 'visitor',
            'guid' => '8bfb5927-62ca-4bc9-b677-d32a5d093714',
            'displayName' => 'Посетитель',
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
  2 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '1cee5ec5-c864-4ffa-8fd5-684f733c11ea',
      'displayName' => 'Супервайзер:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
  3 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '2d580d74-b06e-4590-8b6d-7bcab8a04680',
      'displayName' => 'Гость:mail@faritka.ru:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:mail@faritka.ru:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-21 19:56:47";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
  4 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '46152853-577a-4118-9331-e5bc15ec68d1',
      'displayName' => 'Супервайзер:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зaрегистрированные пользователи:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'userGroup' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'userGroup',
            'type' => 'base',
            'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
            'displayName' => 'Зaрегистрированные пользователи',
          ),
        ),
      ),
    ),
  ),
  5 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '73bb2ca5-d3f1-45f5-825b-d07f521f8933',
      'displayName' => 'Посетитель:Посетитель:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Посетитель:Посетитель:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-13 14:39:17";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'visitor',
            'guid' => '4bb3554b-8480-4838-887e-c40d79e3c882',
            'displayName' => 'Посетитель',
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
            'type' => 'visitor',
            'guid' => '4bb3554b-8480-4838-887e-c40d79e3c882',
            'displayName' => 'Посетитель',
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
  6 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '7797e658-d4c1-40a4-bc87-be68d37b77e7',
      'displayName' => 'Гость:Farit:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:Farit:Зaрегистрированные пользователи:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-09-15 15:56:11";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
      'user' => 
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
      'userGroup' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'userGroup',
            'type' => 'base',
            'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
            'displayName' => 'Зaрегистрированные пользователи',
          ),
        ),
      ),
    ),
  ),
  7 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'a9242ee0-1333-47f1-9bb7-a0b75060aa32',
      'displayName' => 'Посетитель:Посетитель:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Посетитель:Посетитель:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-15 08:44:51";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'visitor',
            'guid' => 'c336db4f-6a38-4561-9206-e97d7f491d99',
            'displayName' => 'Посетитель',
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
            'type' => 'visitor',
            'guid' => 'c336db4f-6a38-4561-9206-e97d7f491d99',
            'displayName' => 'Посетитель',
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
  8 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'aebd6865-a5a4-4814-8f1e-dea80ef3c8fd',
      'displayName' => 'Гость:mail@faritka.ru:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:mail@faritka.ru:Зaрегистрированные пользователи:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-21 19:56:47";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
            'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
            'displayName' => 'Зaрегистрированные пользователи',
          ),
        ),
      ),
    ),
  ),
  9 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'b5b1f921-7ee3-40a8-b317-8d0cdfa96c7d',
      'displayName' => 'Гость:Farit:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:Farit:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-09-15 15:56:11";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
      'user' => 
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
  10 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'c480f095-74ad-49d2-a9c6-cc87a6d49d07',
      'displayName' => 'Супервайзер:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
  11 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'd798b13a-8a14-4780-8286-1a9169d2c4ae',
      'displayName' => 'Гость:mail@faritka.ru:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Гость:mail@faritka.ru:Посетители:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-10-21 19:51:31";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'owner' => 
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
  12 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'ea514028-c9c2-4fc6-912f-b55f379265bd',
      'displayName' => 'Супервайзер:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зaрегистрированные пользователи:ru-RU',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'userGroup' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'userGroup',
            'type' => 'base',
            'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
            'displayName' => 'Зaрегистрированные пользователи',
          ),
        ),
      ),
    ),
  ),
  13 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'efdc8bb6-8466-4beb-8837-49d29148d866',
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
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-08 20:30:10";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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