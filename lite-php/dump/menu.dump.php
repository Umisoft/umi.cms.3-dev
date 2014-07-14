<?php
/**
 * Collection "menu" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'menu',
      'type' => 'menu',
      'guid' => 'e55f7882-73b1-4469-8d9e-56bf55679849',
      'displayName' => 'Нижнее меню',
      'branch' => NULL,
      'slug' => 'bottommenu',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Нижнее меню',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 14:59:34";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'name' => 
      array (
        0 => 'string',
        1 => 'bottomMenu',
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'menu',
      'type' => 'internalItem',
      'guid' => '0e6311ec-a007-42fe-8d17-6bcfcce5e9c0',
      'displayName' => 'Главная',
      'branch' => 
      array (
        'meta' => 
        array (
          'collection' => 'menu',
          'type' => 'menu',
          'guid' => 'e55f7882-73b1-4469-8d9e-56bf55679849',
          'displayName' => 'Нижнее меню',
          'branch' => NULL,
          'slug' => 'bottommenu',
        ),
      ),
      'slug' => 'bottommenu',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Главная',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 14:59:34";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'pageRelation' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'structure',
            'type' => 'static',
            'guid' => 'd534fd83-0f12-4a0d-9853-583b9181a948',
            'displayName' => 'Об отряде',
            'branch' => NULL,
            'slug' => 'ob-otryade',
          ),
        ),
      ),
    ),
  ),
  2 => 
  array (
    'meta' => 
    array (
      'collection' => 'menu',
      'type' => 'internalItem',
      'guid' => '2d04d56a-8034-4d33-9038-9b348b5e9e4d',
      'displayName' => 'Работа, за которую мы никогда не возьмемся',
      'branch' => 
      array (
        'meta' => 
        array (
          'collection' => 'menu',
          'type' => 'menu',
          'guid' => 'e55f7882-73b1-4469-8d9e-56bf55679849',
          'displayName' => 'Нижнее меню',
          'branch' => NULL,
          'slug' => 'bottommenu',
        ),
      ),
      'slug' => 'bottommenu-1',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Работа, за которую мы никогда не возьмемся',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 14:59:34";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'pageRelation' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'structure',
            'type' => 'static',
            'guid' => '3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4',
            'displayName' => 'Работа, за которую мы никогда не возьмемся',
            'branch' => 
            array (
              'meta' => 
              array (
                'collection' => 'structure',
                'type' => 'static',
                'guid' => 'd534fd83-0f12-4a0d-9853-583b9181a948',
                'displayName' => 'Об отряде',
                'branch' => NULL,
                'slug' => 'ob-otryade',
              ),
            ),
            'slug' => 'no',
          ),
        ),
      ),
    ),
  ),
  3 => 
  array (
    'meta' => 
    array (
      'collection' => 'menu',
      'type' => 'internalItem',
      'guid' => 'f8bb0122-97f3-4d36-b21d-9a29a364e402',
      'displayName' => 'Услуги и цены',
      'branch' => 
      array (
        'meta' => 
        array (
          'collection' => 'menu',
          'type' => 'menu',
          'guid' => 'e55f7882-73b1-4469-8d9e-56bf55679849',
          'displayName' => 'Нижнее меню',
          'branch' => NULL,
          'slug' => 'bottommenu',
        ),
      ),
      'slug' => 'bottommenu-2',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Услуги и цены',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 14:59:34";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'pageRelation' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'structure',
            'type' => 'static',
            'guid' => '98751ebf-7f76-4edb-8210-c2c3305bd8a0',
            'displayName' => 'Услуги',
            'branch' => NULL,
            'slug' => 'services',
          ),
        ),
      ),
    ),
  ),
  4 => 
  array (
    'meta' => 
    array (
      'collection' => 'menu',
      'type' => 'externalItem',
      'guid' => 'b87d907b-fc76-47dc-beb6-9254ce2789b4',
      'displayName' => 'Внешняя ссылка',
      'branch' => 
      array (
        'meta' => 
        array (
          'collection' => 'menu',
          'type' => 'menu',
          'guid' => 'e55f7882-73b1-4469-8d9e-56bf55679849',
          'displayName' => 'Нижнее меню',
          'branch' => NULL,
          'slug' => 'bottommenu',
        ),
      ),
      'slug' => 'bottommenu-3',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Внешняя ссылка',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 14:59:34";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'resourceUrl' => 
      array (
        0 => 'string',
        1 => 'http://ya.ru/',
      ),
    ),
  ),
);