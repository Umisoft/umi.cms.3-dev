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
      'guid' => '183162d5-d170-41c4-a9ba-ab9c7fccfbd7',
      'displayName' => 'Супервайзер:Администратор:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Администратор:Зaрегистрированные пользователи:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => '4a9f0130-5e56-49f7-b873-c99807110c81',
            'displayName' => 'Администратор',
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
      'guid' => '255d8bb3-eb7e-4a8e-bdc7-a30544b00196',
      'displayName' => 'Супервайзер:Зарегистрированный пользователь:Комментарии с премодерацией:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зарегистрированный пользователь:Комментарии с премодерацией:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => 'ad4a3c78-e06b-4794-8cc2-7656ce6ff00b',
            'displayName' => 'Зарегистрированный пользователь',
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
            'guid' => '00f1890a-ba43-45bc-af19-f880e8e7840d',
            'displayName' => 'Комментарии с премодерацией',
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
      'guid' => '5e1aed3d-13a1-433e-92a9-1a6d20f505dd',
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
  3 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => '71658974-9977-4c92-9a40-ff8e9c92538b',
      'displayName' => 'Супервайзер:Зарегистрированный пользователь:Авторы с премодерацией постов:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зарегистрированный пользователь:Авторы с премодерацией постов:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => 'ad4a3c78-e06b-4794-8cc2-7656ce6ff00b',
            'displayName' => 'Зарегистрированный пользователь',
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
            'guid' => '1b319b37-735e-452f-9fca-d85c6db89954',
            'displayName' => 'Авторы с премодерацией постов',
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
      'guid' => '751a5ec8-539f-4371-8e99-8edc79c09da1',
      'displayName' => 'Супервайзер:Администратор:Модератор:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Администратор:Модератор:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => '4a9f0130-5e56-49f7-b873-c99807110c81',
            'displayName' => 'Администратор',
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
            'guid' => 'e5b366b2-2651-4eb4-8126-b6028241d3b7',
            'displayName' => 'Модератор',
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
      'guid' => 'a79584d0-5f98-4eb3-9d86-4eee7c4e1cbc',
      'displayName' => 'Супервайзер:Администратор:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Администратор:Посетители:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => '4a9f0130-5e56-49f7-b873-c99807110c81',
            'displayName' => 'Администратор',
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
      'guid' => 'aa420337-f355-4ab4-ac1c-c2bd6f25a047',
      'displayName' => 'Супервайзер:Зарегистрированный пользователь:Зaрегистрированные пользователи:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зарегистрированный пользователь:Зaрегистрированные пользователи:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => 'ad4a3c78-e06b-4794-8cc2-7656ce6ff00b',
            'displayName' => 'Зарегистрированный пользователь',
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
      'guid' => 'd8482cd4-c4e6-480c-a97e-170ce2866178',
      'displayName' => 'Супервайзер:Гость:Комментарии с премодерацией:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Гость:Комментарии с премодерацией:ru-RU',
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
            'guid' => '00f1890a-ba43-45bc-af19-f880e8e7840d',
            'displayName' => 'Комментарии с премодерацией',
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
      'guid' => 'e774f9b9-bb37-4e95-9932-2effe6fb432e',
      'displayName' => 'Супервайзер:Зарегистрированный пользователь:Посетители:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Зарегистрированный пользователь:Посетители:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => 'ad4a3c78-e06b-4794-8cc2-7656ce6ff00b',
            'displayName' => 'Зарегистрированный пользователь',
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
  9 => 
  array (
    'meta' => 
    array (
      'collection' => 'userUserGroup',
      'type' => 'base',
      'guid' => 'ffacfd1b-8f65-45f6-aa56-83f984cb5f68',
      'displayName' => 'Супервайзер:Администратор:Администраторы:ru-RU',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзер:Администратор:Администраторы:ru-RU',
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
      'user' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'user',
            'type' => 'registered',
            'guid' => '4a9f0130-5e56-49f7-b873-c99807110c81',
            'displayName' => 'Администратор',
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
            'guid' => '3cfdd048-5fb9-4d72-a888-acf0483a2ae2',
            'displayName' => 'Администраторы',
          ),
        ),
      ),
    ),
  ),
);