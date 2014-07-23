<?php
/**
 * Collection "userGroup" dump.
 */
return array (
  0 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => 'bedcbbac-7dd1-4b60-979a-f7d944ecb08a',
      'displayName' => 'Посетители',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Посетители',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:7:{s:7:"project";a:4:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";i:2;s:12:"siteExecutor";i:3;s:13:"adminExecutor";}s:13:"project.admin";a:2:{i:0;s:6:"viewer";i:1;s:12:"restExecutor";}s:18:"project.admin.rest";a:1:{i:0;s:6:"viewer";}s:12:"project.site";a:4:{i:0;s:17:"structureExecutor";i:1;s:14:"searchExecutor";i:2;s:6:"viewer";i:3;s:14:"widgetExecutor";}s:19:"project.site.search";a:1:{i:0;s:6:"viewer";}s:22:"project.site.structure";a:2:{i:0;s:12:"menuExecutor";i:1;s:6:"viewer";}s:27:"project.site.structure.menu";a:1:{i:0;s:6:"viewer";}}',
      ),
    ),
  ),
);