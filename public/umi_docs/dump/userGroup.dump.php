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
      'guid' => 'a2bbe8f8-7d7c-4d34-b496-94f3e2212109',
      'displayName' => 'Супервайзеры',
    ),
    'data' => 
    array (
      'displayName#ru-RU' => 
      array (
        0 => 'string',
        1 => 'Супервайзеры',
      ),
      'created' => 
      array (
        0 => 'object',
        1 => 'O:8:"DateTime":3:{s:4:"date";s:19:"2014-08-02 07:29:55";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}',
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
      'locked' => 
      array (
        0 => 'boolean',
        1 => false,
      ),
      'roles' => 
      array (
        0 => 'array',
        1 => 'a:89:{s:7:"project";a:2:{i:0;s:12:"siteExecutor";i:1;s:13:"adminExecutor";}s:12:"project.site";a:8:{i:0;s:12:"blogExecutor";i:1;s:12:"newsExecutor";i:2;s:14:"searchExecutor";i:3;s:17:"structureExecutor";i:4;s:12:"topBarViewer";i:5;s:13:"usersExecutor";i:6;s:6:"viewer";i:7;s:14:"widgetExecutor";}s:22:"project.site.structure";a:3:{i:0;s:6:"viewer";i:1;s:12:"menuExecutor";i:2;s:17:"infoblockExecutor";}s:27:"project.site.structure.menu";a:1:{i:0;s:6:"viewer";}s:32:"project.site.structure.infoblock";a:1:{i:0;s:6:"viewer";}s:17:"project.site.news";a:4:{i:0;s:6:"viewer";i:1;s:14:"rubricExecutor";i:2;s:12:"itemExecutor";i:3;s:15:"subjectExecutor";}s:24:"project.site.news.rubric";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:22:"project.site.news.item";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:25:"project.site.news.subject";a:2:{i:0;s:6:"viewer";i:1;s:9:"rssViewer";}s:17:"project.site.blog";a:9:{i:0;s:6:"viewer";i:1;s:12:"postExecutor";i:2;s:13:"draftExecutor";i:3;s:16:"moderateExecutor";i:4;s:14:"rejectExecutor";i:5;s:16:"categoryExecutor";i:6;s:14:"authorExecutor";i:7;s:11:"tagExecutor";i:8;s:15:"commentExecutor";}s:22:"project.site.blog.post";a:7:{i:0;s:9:"rssViewer";i:1;s:6:"author";i:2;s:9:"moderator";i:3;s:6:"viewer";i:4;s:11:"addExecutor";i:5;s:12:"editExecutor";i:6;s:12:"viewExecutor";}s:26:"project.site.blog.post.add";a:1:{i:0;s:6:"author";}s:27:"project.site.blog.post.edit";a:1:{i:0;s:9:"moderator";}s:27:"project.site.blog.post.view";a:1:{i:0;s:6:"viewer";}s:23:"project.site.blog.draft";a:5:{i:0;s:6:"author";i:1;s:9:"publisher";i:2;s:6:"viewer";i:3;s:12:"editExecutor";i:4;s:12:"viewExecutor";}s:28:"project.site.blog.draft.edit";a:1:{i:0;s:6:"author";}s:28:"project.site.blog.draft.view";a:1:{i:0;s:6:"viewer";}s:26:"project.site.blog.moderate";a:5:{i:0;s:9:"moderator";i:1;s:6:"viewer";i:2;s:12:"editExecutor";i:3;s:11:"ownExecutor";i:4;s:11:"allExecutor";}s:31:"project.site.blog.moderate.edit";a:2:{i:0;s:6:"author";i:1;s:9:"moderator";}s:30:"project.site.blog.moderate.own";a:1:{i:0;s:6:"viewer";}s:30:"project.site.blog.moderate.all";a:1:{i:0;s:6:"viewer";}s:24:"project.site.blog.reject";a:4:{i:0;s:6:"author";i:1;s:6:"viewer";i:2;s:12:"editExecutor";i:3;s:12:"viewExecutor";}s:29:"project.site.blog.reject.edit";a:1:{i:0;s:6:"author";}s:29:"project.site.blog.reject.view";a:1:{i:0;s:6:"viewer";}s:26:"project.site.blog.category";a:2:{i:0;s:9:"rssViewer";i:1;s:6:"viewer";}s:24:"project.site.blog.author";a:4:{i:0;s:9:"rssViewer";i:1;s:6:"viewer";i:2;s:15:"profileExecutor";i:3;s:12:"viewExecutor";}s:32:"project.site.blog.author.profile";a:1:{i:0;s:6:"author";}s:29:"project.site.blog.author.view";a:1:{i:0;s:6:"viewer";}s:21:"project.site.blog.tag";a:2:{i:0;s:9:"rssViewer";i:1;s:6:"viewer";}s:25:"project.site.blog.comment";a:3:{i:0;s:9:"moderator";i:1;s:6:"viewer";i:2;s:11:"addExecutor";}s:29:"project.site.blog.comment.add";a:2:{i:0;s:11:"commentator";i:1;s:24:"commentatorPremoderation";}s:19:"project.site.search";a:1:{i:0;s:6:"viewer";}s:18:"project.site.users";a:5:{i:0;s:6:"viewer";i:1;s:21:"authorizationExecutor";i:2;s:20:"registrationExecutor";i:3;s:19:"restorationExecutor";i:4;s:15:"profileExecutor";}s:32:"project.site.users.authorization";a:1:{i:0;s:6:"viewer";}s:31:"project.site.users.registration";a:2:{i:0;s:6:"viewer";i:1;s:18:"activationExecutor";}s:42:"project.site.users.registration.activation";a:1:{i:0;s:6:"viewer";}s:30:"project.site.users.restoration";a:2:{i:0;s:6:"viewer";i:1;s:20:"confirmationExecutor";}s:43:"project.site.users.restoration.confirmation";a:1:{i:0;s:6:"viewer";}s:26:"project.site.users.profile";a:2:{i:0;s:6:"viewer";i:1;s:16:"passwordExecutor";}s:35:"project.site.users.profile.password";a:1:{i:0;s:6:"viewer";}s:13:"project.admin";a:2:{i:0;s:12:"restExecutor";i:1;s:6:"viewer";}s:18:"project.admin.rest";a:9:{i:0;s:6:"viewer";i:1;s:17:"structureExecutor";i:2;s:13:"usersExecutor";i:3;s:12:"newsExecutor";i:4;s:12:"blogExecutor";i:5;s:11:"seoExecutor";i:6;s:13:"filesExecutor";i:7;s:15:"serviceExecutor";i:8;s:16:"settingsExecutor";}s:28:"project.admin.rest.structure";a:4:{i:0;s:12:"pageExecutor";i:1;s:14:"layoutExecutor";i:2;s:17:"infoblockExecutor";i:3;s:12:"menuExecutor";}s:33:"project.admin.rest.structure.page";a:1:{i:0;s:6:"editor";}s:35:"project.admin.rest.structure.layout";a:1:{i:0;s:6:"editor";}s:38:"project.admin.rest.structure.infoblock";a:1:{i:0;s:6:"editor";}s:33:"project.admin.rest.structure.menu";a:1:{i:0;s:6:"editor";}s:24:"project.admin.rest.users";a:3:{i:0;s:12:"userExecutor";i:1;s:13:"groupExecutor";i:2;s:17:"usergroupExecutor";}s:29:"project.admin.rest.users.user";a:1:{i:0;s:6:"editor";}s:30:"project.admin.rest.users.group";a:1:{i:0;s:6:"editor";}s:34:"project.admin.rest.users.usergroup";a:1:{i:0;s:6:"editor";}s:23:"project.admin.rest.news";a:5:{i:0;s:12:"itemExecutor";i:1;s:14:"rubricExecutor";i:2;s:15:"subjectExecutor";i:3;s:19:"itemsubjectExecutor";i:4;s:18:"rsssubjectExecutor";}s:28:"project.admin.rest.news.item";a:1:{i:0;s:6:"editor";}s:30:"project.admin.rest.news.rubric";a:1:{i:0;s:6:"editor";}s:31:"project.admin.rest.news.subject";a:1:{i:0;s:6:"editor";}s:35:"project.admin.rest.news.itemsubject";a:1:{i:0;s:6:"editor";}s:34:"project.admin.rest.news.rsssubject";a:1:{i:0;s:6:"editor";}s:23:"project.admin.rest.blog";a:9:{i:0;s:12:"postExecutor";i:1;s:16:"categoryExecutor";i:2;s:14:"authorExecutor";i:3;s:15:"commentExecutor";i:4;s:11:"tagExecutor";i:5;s:15:"posttagExecutor";i:6;s:14:"rsstagExecutor";i:7;s:18:"poststatusExecutor";i:8;s:21:"commentstatusExecutor";}s:28:"project.admin.rest.blog.post";a:1:{i:0;s:6:"editor";}s:32:"project.admin.rest.blog.category";a:1:{i:0;s:6:"editor";}s:30:"project.admin.rest.blog.author";a:1:{i:0;s:6:"editor";}s:31:"project.admin.rest.blog.comment";a:1:{i:0;s:6:"editor";}s:27:"project.admin.rest.blog.tag";a:1:{i:0;s:6:"editor";}s:31:"project.admin.rest.blog.posttag";a:1:{i:0;s:6:"editor";}s:30:"project.admin.rest.blog.rsstag";a:1:{i:0;s:6:"editor";}s:34:"project.admin.rest.blog.poststatus";a:1:{i:0;s:6:"editor";}s:37:"project.admin.rest.blog.commentstatus";a:1:{i:0;s:6:"editor";}s:22:"project.admin.rest.seo";a:3:{i:0;s:14:"robotsExecutor";i:1;s:17:"megaindexExecutor";i:2;s:14:"yandexExecutor";}s:29:"project.admin.rest.seo.robots";a:1:{i:0;s:6:"editor";}s:24:"project.admin.rest.files";a:1:{i:0;s:15:"managerExecutor";}s:26:"project.admin.rest.service";a:2:{i:0;s:14:"updateExecutor";i:1;s:15:"recycleExecutor";}s:27:"project.admin.rest.settings";a:5:{i:0;s:12:"siteExecutor";i:1;s:13:"usersExecutor";i:2;s:15:"serviceExecutor";i:3;s:11:"seoExecutor";i:4;s:13:"formsExecutor";}s:32:"project.admin.rest.settings.site";a:6:{i:0;s:14:"commonExecutor";i:1;s:12:"mailExecutor";i:2;s:11:"seoExecutor";i:3;s:18:"templatingExecutor";i:4;s:15:"slugifyExecutor";i:5;s:15:"licenseExecutor";}s:39:"project.admin.rest.settings.site.common";a:1:{i:0;s:12:"configurator";}s:37:"project.admin.rest.settings.site.mail";a:1:{i:0;s:12:"configurator";}s:36:"project.admin.rest.settings.site.seo";a:1:{i:0;s:12:"configurator";}s:43:"project.admin.rest.settings.site.templating";a:1:{i:0;s:12:"configurator";}s:40:"project.admin.rest.settings.site.slugify";a:1:{i:0;s:12:"configurator";}s:40:"project.admin.rest.settings.site.license";a:1:{i:0;s:12:"configurator";}s:33:"project.admin.rest.settings.users";a:2:{i:0;s:21:"notificationsExecutor";i:1;s:20:"registrationExecutor";}s:47:"project.admin.rest.settings.users.notifications";a:1:{i:0;s:12:"configurator";}s:46:"project.admin.rest.settings.users.registration";a:1:{i:0;s:12:"configurator";}s:35:"project.admin.rest.settings.service";a:1:{i:0;s:14:"backupExecutor";}s:42:"project.admin.rest.settings.service.backup";a:1:{i:0;s:12:"configurator";}s:31:"project.admin.rest.settings.seo";a:2:{i:0;s:17:"megaindexExecutor";i:1;s:14:"yandexExecutor";}s:41:"project.admin.rest.settings.seo.megaindex";a:1:{i:0;s:12:"configurator";}s:38:"project.admin.rest.settings.seo.yandex";a:1:{i:0;s:12:"configurator";}s:33:"project.admin.rest.settings.forms";a:1:{i:0;s:15:"captchaExecutor";}s:41:"project.admin.rest.settings.forms.captcha";a:1:{i:0;s:12:"configurator";}}',
      ),
    ),
  ),
  1 => 
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