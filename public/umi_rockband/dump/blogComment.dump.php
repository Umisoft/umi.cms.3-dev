<?php
/**
 * Collection "blogComment" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'blogComment',
      'type' => 'branchComment',
      'guid' => '094ef5b3-2e6d-4606-bc77-d69b8dff8bf8',
      'displayName' => 'Видеоблог',
      'branch' => NULL,
      'slug' => '094ef5b3-2e6d-4606-bc77-d69b8dff8bf8',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Видеоблог',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-20 20:26:08";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'updated' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-30 15:04:19";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'trashed' => 
      array (
        0 => 'boolean',
        1 => false,
      ),
      'post' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'blogPost',
            'type' => 'base',
            'guid' => '0045690a-8cca-4c60-a85f-4ad51de7e8d0',
            'displayName' => 'Видеоблог',
            'slug' => 'video-blog',
          ),
        ),
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'blogComment',
      'type' => 'branchComment',
      'guid' => 'c91f4d71-6338-430a-988d-60d58a01b48f',
      'displayName' => 'Немного истории',
      'branch' => NULL,
      'slug' => 'c91f4d71-6338-430a-988d-60d58a01b48f',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Немного истории',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-29 15:14:02";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
      ),
      'updated' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-30 15:04:22";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'trashed' => 
      array (
        0 => 'boolean',
        1 => false,
      ),
      'post' => 
      array (
        0 => 'relation',
        1 => 
        array (
          'meta' => 
          array (
            'collection' => 'blogPost',
            'type' => 'base',
            'guid' => 'a402dc52-672a-412d-8a3b-50c4aecbe7be',
            'displayName' => 'Немного истории',
            'slug' => 'little-history',
          ),
        ),
      ),
    ),
  ),
);