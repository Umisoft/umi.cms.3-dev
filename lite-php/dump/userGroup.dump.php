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
      'guid' => '00f1890a-ba43-45bc-af19-f880e8e7840d',
      'displayName' => 'Комментарии с премодерацией',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Комментарии с премодерацией',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Comment with premoderation',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:3:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:25:"project.site.blog.comment";a:1:{i:0;s:11:"addExecutor";}s:29:"project.site.blog.comment.add";a:1:{i:0;s:24:"commentatorPremoderation";}}',
      ),
    ),
  ),
  1 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => '1b319b37-735e-452f-9fca-d85c6db89954',
      'displayName' => 'Авторы с премодерацией постов',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Авторы с премодерацией постов',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Authors with premoderation',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:15:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:17:"project.site.blog";a:3:{i:0;s:13:"draftExecutor";i:1;s:16:"moderateExecutor";i:2;s:14:"rejectExecutor";}s:23:"project.site.blog.draft";a:4:{i:0;s:12:"editExecutor";i:1;s:12:"viewExecutor";i:2;s:6:"viewer";i:3;s:6:"author";}s:28:"project.site.blog.draft.edit";a:1:{i:0;s:6:"author";}s:28:"project.site.blog.draft.view";a:1:{i:0;s:6:"viewer";}s:26:"project.site.blog.moderate";a:3:{i:0;s:12:"editExecutor";i:1;s:11:"ownExecutor";i:2;s:6:"viewer";}s:31:"project.site.blog.moderate.edit";a:1:{i:0;s:6:"author";}s:30:"project.site.blog.moderate.own";a:1:{i:0;s:6:"viewer";}s:22:"project.site.blog.post";a:5:{i:0;s:11:"addExecutor";i:1;s:12:"editExecutor";i:2;s:12:"viewExecutor";i:3;s:6:"viewer";i:4;s:6:"author";}s:26:"project.site.blog.post.add";a:1:{i:0;s:6:"author";}s:24:"project.site.blog.reject";a:4:{i:0;s:12:"editExecutor";i:1;s:12:"viewExecutor";i:2;s:6:"viewer";i:3;s:6:"author";}s:29:"project.site.blog.reject.edit";a:1:{i:0;s:6:"author";}s:29:"project.site.blog.reject.view";a:2:{i:0;s:6:"viewer";i:1;s:6:"author";}s:24:"project.site.blog.author";a:1:{i:0;s:15:"profileExecutor";}s:32:"project.site.blog.author.profile";a:1:{i:0;s:6:"author";}}',
      ),
    ),
  ),
  2 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => '3cfdd048-5fb9-4d72-a888-acf0483a2ae2',
      'displayName' => 'Администраторы',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Администраторы',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Administrator',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:11:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:18:"project.admin.rest";a:3:{i:0;s:12:"newsExecutor";i:1;s:17:"structureExecutor";i:2;s:13:"usersExecutor";}s:23:"project.admin.rest.news";a:3:{i:0;s:14:"rubricExecutor";i:1;s:12:"itemExecutor";i:2;s:15:"subjectExecutor";}s:28:"project.admin.rest.news.item";a:1:{i:0;s:6:"editor";}s:30:"project.admin.rest.news.rubric";a:1:{i:0;s:6:"editor";}s:31:"project.admin.rest.news.subject";a:1:{i:0;s:6:"editor";}s:28:"project.admin.rest.structure";a:2:{i:0;s:12:"pageExecutor";i:1;s:14:"layoutExecutor";}s:33:"project.admin.rest.structure.page";a:1:{i:0;s:6:"editor";}s:35:"project.admin.rest.structure.layout";a:1:{i:0;s:6:"editor";}s:24:"project.admin.rest.users";a:1:{i:0;s:12:"userExecutor";}s:29:"project.admin.rest.users.user";a:1:{i:0;s:6:"editor";}}',
      ),
    ),
  ),
  3 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => '52d67f8a-80e7-4494-8ff9-f74c0ec3f31b',
      'displayName' => 'Авторы без премодерации постов',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Авторы без премодерации постов',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Authors without premoderation',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:14:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:17:"project.site.blog";a:3:{i:0;s:13:"draftExecutor";i:1;s:16:"moderateExecutor";i:2;s:14:"rejectExecutor";}s:23:"project.site.blog.draft";a:4:{i:0;s:12:"editExecutor";i:1;s:12:"viewExecutor";i:2;s:6:"viewer";i:3;s:9:"publisher";}s:28:"project.site.blog.draft.edit";a:1:{i:0;s:6:"author";}s:28:"project.site.blog.draft.view";a:1:{i:0;s:6:"viewer";}s:26:"project.site.blog.moderate";a:3:{i:0;s:12:"editExecutor";i:1;s:12:"viewExecutor";i:2;s:6:"viewer";}s:31:"project.site.blog.moderate.edit";a:1:{i:0;s:6:"author";}s:30:"project.site.blog.moderate.own";a:1:{i:0;s:6:"viewer";}s:22:"project.site.blog.post";a:5:{i:0;s:11:"addExecutor";i:1;s:12:"editExecutor";i:2;s:12:"viewExecutor";i:3;s:6:"viewer";i:4;s:6:"author";}s:26:"project.site.blog.post.add";a:1:{i:0;s:6:"author";}s:24:"project.site.blog.reject";a:4:{i:0;s:12:"editExecutor";i:1;s:12:"viewExecutor";i:2;s:6:"viewer";i:3;s:6:"author";}s:29:"project.site.blog.reject.edit";a:1:{i:0;s:6:"author";}s:29:"project.site.blog.reject.view";a:2:{i:0;s:6:"viewer";i:1;s:6:"author";}s:32:"project.site.blog.author.profile";a:1:{i:0;s:6:"author";}}',
      ),
    ),
  ),
  4 => 
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
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Visitors',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:26:{s:7:"project";a:4:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";i:2;s:12:"siteExecutor";i:3;s:13:"adminExecutor";}s:13:"project.admin";a:2:{i:0;s:6:"viewer";i:1;s:12:"restExecutor";}s:18:"project.admin.rest";a:1:{i:0;s:6:"viewer";}s:12:"project.site";a:8:{i:0;s:13:"usersExecutor";i:1;s:12:"newsExecutor";i:2;s:17:"structureExecutor";i:3;s:12:"blogExecutor";i:4;s:14:"searchExecutor";i:5;s:6:"viewer";i:6;s:14:"widgetExecutor";i:7;s:14:"searchExecutor";}s:19:"project.site.search";a:1:{i:0;s:6:"viewer";}s:18:"project.site.users";a:4:{i:0;s:21:"authorizationExecutor";i:1;s:20:"registrationExecutor";i:2;s:19:"restorationExecutor";i:3;s:6:"viewer";}s:32:"project.site.users.authorization";a:1:{i:0;s:6:"viewer";}s:31:"project.site.users.registration";a:2:{i:0;s:18:"activationExecutor";i:1;s:6:"viewer";}s:42:"project.site.users.registration.activation";a:1:{i:0;s:6:"viewer";}s:30:"project.site.users.restoration";a:2:{i:0;s:20:"confirmationExecutor";i:1;s:6:"viewer";}s:43:"project.site.users.restoration.confirmation";a:1:{i:0;s:6:"viewer";}s:22:"project.site.structure";a:3:{i:0;s:12:"menuExecutor";i:1;s:17:"infoblockExecutor";i:2;s:6:"viewer";}s:27:"project.site.structure.menu";a:1:{i:0;s:6:"viewer";}s:32:"project.site.structure.infoblock";a:1:{i:0;s:6:"viewer";}s:17:"project.site.news";a:4:{i:0;s:12:"itemExecutor";i:1;s:14:"rubricExecutor";i:2;s:15:"subjectExecutor";i:3;s:6:"viewer";}s:22:"project.site.news.item";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:24:"project.site.news.rubric";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:25:"project.site.news.subject";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:17:"project.site.blog";a:6:{i:0;s:16:"categoryExecutor";i:1;s:12:"postExecutor";i:2;s:11:"tagExecutor";i:3;s:14:"authorExecutor";i:4;s:15:"commentExecutor";i:5;s:6:"viewer";}s:22:"project.site.blog.post";a:3:{i:0;s:12:"viewExecutor";i:1;s:6:"viewer";i:2;s:9:"rssViewer";}s:27:"project.site.blog.post.view";a:1:{i:0;s:6:"viewer";}s:26:"project.site.blog.category";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:21:"project.site.blog.tag";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:24:"project.site.blog.author";a:3:{i:0;s:12:"viewExecutor";i:1;s:6:"viewer";i:2;s:9:"rssViewer";}s:29:"project.site.blog.author.view";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:25:"project.site.blog.comment";a:1:{i:0;s:6:"viewer";}}',
      ),
    ),
  ),
  5 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => 'd83f5ad6-1a72-4922-b21f-456814bc0b0d',
      'displayName' => 'Комментарии без премодерацией',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Комментарии без премодерацией',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Comment without premoderation',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:3:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:25:"project.site.blog.comment";a:1:{i:0;s:11:"addExecutor";}s:29:"project.site.blog.comment.add";a:1:{i:0;s:11:"commentator";}}',
      ),
    ),
  ),
  6 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => 'daabebf8-f3b3-4f62-a23d-522eff9b7f68',
      'displayName' => 'Зaрегистрированные пользователи',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Зaрегистрированные пользователи',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Registered users',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:4:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:18:"project.site.users";a:2:{i:0;s:15:"profileExecutor";i:1;s:6:"viewer";}s:26:"project.site.users.profile";a:2:{i:0;s:16:"passwordExecutor";i:1;s:6:"viewer";}s:35:"project.site.users.profile.password";a:1:{i:0;s:6:"viewer";}}',
      ),
    ),
  ),
  7 => 
  array (
    'meta' => 
    array (
      'collection' => 'userGroup',
      'type' => 'base',
      'guid' => 'e5b366b2-2651-4eb4-8126-b6028241d3b7',
      'displayName' => 'Модератор',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Модератор',
      ),
      'displayName#en-US' => 
      array (
        0 => 'string',
        1 => 'Moderator',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-07-14 16:04:49";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}',
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
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:8:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:17:"project.site.blog";a:3:{i:0;s:13:"draftExecutor";i:1;s:16:"moderateExecutor";i:2;s:14:"rejectExecutor";}s:25:"project.site.blog.comment";a:1:{i:0;s:9:"moderator";}s:26:"project.site.blog.moderate";a:4:{i:0;s:12:"editExecutor";i:1;s:11:"ownExecutor";i:2;s:11:"allExecutor";i:3;s:9:"moderator";}s:31:"project.site.blog.moderate.edit";a:1:{i:0;s:9:"moderator";}s:30:"project.site.blog.moderate.all";a:1:{i:0;s:6:"viewer";}s:22:"project.site.blog.post";a:4:{i:0;s:11:"addExecutor";i:1;s:12:"editExecutor";i:2;s:12:"viewExecutor";i:3;s:9:"moderator";}s:27:"project.site.blog.post.edit";a:1:{i:0;s:9:"moderator";}}',
      ),
    ),
  ),
);