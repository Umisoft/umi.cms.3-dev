<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsObject;
use umicms\orm\object\ICmsPage;

return [

    'project.admin' => '{#lazy:~/project/admin/i18n/dictionary.config.php}',
    'project.admin.rest' => '{#lazy:~/project/admin/rest/i18n/dictionary.config.php}',

    'project.admin.rest.news' => '{#lazy:~/project/module/news/admin/i18n/dictionary.config.php}',
    'project.admin.rest.news.item' => '{#lazy:~/project/module/news/admin/item/i18n/dictionary.config.php}',
    'project.admin.rest.news.itemsubject' => '{#lazy:~/project/module/news/admin/itemsubject/i18n/dictionary.config.php}',
    'project.admin.rest.news.rubric' => '{#lazy:~/project/module/news/admin/rubric/i18n/dictionary.config.php}',
    'project.admin.rest.news.subject' => '{#lazy:~/project/module/news/admin/subject/i18n/dictionary.config.php}',
    'project.admin.rest.news.rss' => '{#lazy:~/project/module/news/admin/rss/i18n/dictionary.config.php}',
    'project.admin.rest.news.rsssubject' => '{#lazy:~/project/module/news/admin/rsssubject/i18n/dictionary.config.php}',

    'project.admin.rest.files' => '{#lazy:~/project/module/files/admin/i18n/dictionary.config.php}',
    'project.admin.rest.files.manager' => '{#lazy:~/project/module/files/admin/manager/i18n/dictionary.config.php}',

    'project.admin.rest.users' => '{#lazy:~/project/module/users/admin/i18n/dictionary.config.php}',
    'project.admin.rest.users.user' => '{#lazy:~/project/module/users/admin/user/i18n/dictionary.config.php}',
    'project.admin.rest.users.group' => '{#lazy:~/project/module/users/admin/group/i18n/dictionary.config.php}',
    'project.admin.rest.users.usergroup' => '{#lazy:~/project/module/users/admin/usergroup/i18n/dictionary.config.php}',

    'project.admin.rest.structure' => '{#lazy:~/project/module/structure/admin/i18n/dictionary.config.php}',
    'project.admin.rest.structure.page' => '{#lazy:~/project/module/structure/admin/page/i18n/dictionary.config.php}',
    'project.admin.rest.structure.layout' => '{#lazy:~/project/module/structure/admin/layout/i18n/dictionary.config.php}',
    'project.admin.rest.structure.infoblock' => '{#lazy:~/project/module/structure/admin/infoblock/i18n/dictionary.config.php}',
    'project.admin.rest.structure.menu' => '{#lazy:~/project/module/structure/admin/menu/i18n/dictionary.config.php}',

    'project.admin.rest.statistics' => '{#lazy:~/project/module/statistics/admin/i18n/dictionary.config.php}',
    'project.admin.rest.statistics.metrika' => '{#lazy:~/project/module/statistics/admin/metrika/i18n/dictionary.config.php}',

    'project.admin.rest.service' => '{#lazy:~/project/module/service/admin/i18n/dictionary.config.php}',
    'project.admin.rest.service.backup' => '{#lazy:~/project/module/service/admin/backup/i18n/dictionary.config.php}',
    'project.admin.rest.service.update' => '{#lazy:~/project/module/service/admin/update/i18n/dictionary.config.php}',
    'project.admin.rest.service.recycle' => '{#lazy:~/project/module/service/admin/recycle/i18n/dictionary.config.php}',

    'project.admin.rest.seo' => '{#lazy:~/project/module/seo/admin/i18n/dictionary.config.php}',
    'project.admin.rest.seo.megaindex' => '{#lazy:~/project/module/seo/admin/megaindex/i18n/dictionary.config.php}',
    'project.admin.rest.seo.yandex' => '{#lazy:~/project/module/seo/admin/yandex/i18n/dictionary.config.php}',
    'project.admin.rest.seo.robots' => '{#lazy:~/project/module/seo/admin/robots/i18n/dictionary.config.php}',

    'project.admin.rest.blog' => '{#lazy:~/project/module/blog/admin/i18n/dictionary.config.php}',
    'project.admin.rest.blog.category' => '{#lazy:~/project/module/blog/admin/category/i18n/dictionary.config.php}',
    'project.admin.rest.blog.comment' => '{#lazy:~/project/module/blog/admin/comment/i18n/dictionary.config.php}',
    'project.admin.rest.blog.post' => '{#lazy:~/project/module/blog/admin/post/i18n/dictionary.config.php}',
    'project.admin.rest.blog.posttag' => '{#lazy:~/project/module/blog/admin/posttag/i18n/dictionary.config.php}',
    'project.admin.rest.blog.author' => '{#lazy:~/project/module/blog/admin/author/i18n/dictionary.config.php}',
    'project.admin.rest.blog.tag' => '{#lazy:~/project/module/blog/admin/tag/i18n/dictionary.config.php}',
    'project.admin.rest.blog.rss' => '{#lazy:~/project/module/blog/admin/rss/i18n/dictionary.config.php}',
    'project.admin.rest.blog.rsstag' => '{#lazy:~/project/module/blog/admin/rsstag/i18n/dictionary.config.php}',
    'project.admin.rest.blog.poststatus' => '{#lazy:~/project/module/blog/admin/poststatus/i18n/dictionary.config.php}',
    'project.admin.rest.blog.commentstatus' => '{#lazy:~/project/module/blog/admin/commentstatus/i18n/dictionary.config.php}',

    'project.admin.rest.surveys' => '{#lazy:~/project/module/surveys/admin/i18n/dictionary.config.php}',
    'project.admin.rest.surveys.survey' => '{#lazy:~/project/module/surveys/admin/survey/i18n/dictionary.config.php}',
    'project.admin.rest.surveys.answer' => '{#lazy:~/project/module/surveys/admin/answer/i18n/dictionary.config.php}',

    'project.admin.rest.models' => '{#lazy:~/project/module/models/admin/i18n/dictionary.config.php}',

    'project.admin.rest.settings' => '{#lazy:~/project/module/settings/admin/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site' => '{#lazy:~/project/site/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.slugify' => '{#lazy:~/project/site/settings/slugify/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.common' => '{#lazy:~/project/site/settings/common/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.license' => '{#lazy:~/project/site/settings/license/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.seo' => '{#lazy:~/project/site/settings/seo/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.templating' => '{#lazy:~/project/site/settings/templating/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.mail' => '{#lazy:~/project/site/settings/mail/i18n/dictionary.config.php}',
    'project.admin.rest.settings.site.siteService' => '{#lazy:~/project/site/settings/siteService/i18n/dictionary.config.php}',
    'project.admin.rest.settings.service' => '{#lazy:~/project/module/service/admin/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.service.backup' => '{#lazy:~/project/module/service/admin/settings/backup/i18n/dictionary.config.php}',
    'project.admin.rest.settings.seo' => '{#lazy:~/project/module/seo/admin/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.seo.megaindex' => '{#lazy:~/project/module/seo/admin/settings/megaindex/i18n/dictionary.config.php}',
    'project.admin.rest.settings.seo.yandex' => '{#lazy:~/project/module/seo/admin/settings/yandex/i18n/dictionary.config.php}',
    'project.admin.rest.settings.statistics' => '{#lazy:~/project/module/statistics/admin/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.statistics.metrika' => '{#lazy:~/project/module/statistics/admin/settings/metrika/i18n/dictionary.config.php}',
    'project.admin.rest.settings.users' => '{#lazy:~/project/module/users/admin/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.users.registration' => '{#lazy:~/project/module/users/admin/settings/registration/i18n/dictionary.config.php}',
    'project.admin.rest.settings.users.notifications' => '{#lazy:~/project/module/users/admin/settings/notifications/i18n/dictionary.config.php}',

    'project.admin.rest.settings.forms' => '{#lazy:~/project/module/forms/admin/settings/i18n/dictionary.config.php}',
    'project.admin.rest.settings.forms.captcha' => '{#lazy:~/project/module/forms/admin/settings/captcha/i18n/dictionary.config.php}',

    'project.site' => '{#lazy:~/project/site/i18n/dictionary.config.php}',

    'project.site.structure' => '{#lazy:~/project/module/structure/site/i18n/dictionary.config.php}',
    'project.site.structure.menu' => '{#lazy:~/project/module/structure/site/menu/i18n/dictionary.config.php}',
    'project.site.structure.infoblock' => '{#lazy:~/project/module/structure/site/infoblock/i18n/dictionary.config.php}',

    'project.site.news' => '{#lazy:~/project/module/news/site/i18n/dictionary.config.php}',
    'project.site.news.item' => '{#lazy:~/project/module/news/site/item/i18n/dictionary.config.php}',
    'project.site.news.rubric' => '{#lazy:~/project/module/news/site/rubric/i18n/dictionary.config.php}',
    'project.site.news.subject' => '{#lazy:~/project/module/news/site/subject/i18n/dictionary.config.php}',

    'project.site.users' => '{#lazy:~/project/module/users/site/i18n/dictionary.config.php}',
    'project.site.users.authorization' => '{#lazy:~/project/module/users/site/authorization/i18n/dictionary.config.php}',
    'project.site.users.registration' => '{#lazy:~/project/module/users/site/registration/i18n/dictionary.config.php}',
    'project.site.users.registration.activation' => '{#lazy:~/project/module/users/site/registration/activation/i18n/dictionary.config.php}',
    'project.site.users.restoration' => '{#lazy:~/project/module/users/site/restoration/i18n/dictionary.config.php}',
    'project.site.users.restoration.confirmation' => '{#lazy:~/project/module/users/site/restoration/confirmation/i18n/dictionary.config.php}',
    'project.site.users.profile' => '{#lazy:~/project/module/users/site/profile/i18n/dictionary.config.php}',
    'project.site.users.profile.password' => '{#lazy:~/project/module/users/site/profile/password/i18n/dictionary.config.php}',

    'project.site.blog' => '{#lazy:~/project/module/blog/site/i18n/dictionary.config.php}',
    'project.site.blog.comment' => '{#lazy:~/project/module/blog/site/comment/i18n/dictionary.config.php}',
    'project.site.blog.comment.add' => '{#lazy:~/project/module/blog/site/comment/add/i18n/dictionary.config.php}',
    'project.site.blog.post' => '{#lazy:~/project/module/blog/site/post/i18n/dictionary.config.php}',
    'project.site.blog.post.add' => '{#lazy:~/project/module/blog/site/post/add/i18n/dictionary.config.php}',
    'project.site.blog.post.edit' => '{#lazy:~/project/module/blog/site/post/edit/i18n/dictionary.config.php}',
    'project.site.blog.post.view' => '{#lazy:~/project/module/blog/site/post/view/i18n/dictionary.config.php}',
    'project.site.blog.draft' => '{#lazy:~/project/module/blog/site/draft/i18n/dictionary.config.php}',
    'project.site.blog.draft.edit' => '{#lazy:~/project/module/blog/site/draft/edit/i18n/dictionary.config.php}',
    'project.site.blog.draft.view' => '{#lazy:~/project/module/blog/site/draft/view/i18n/dictionary.config.php}',
    'project.site.blog.moderate' => '{#lazy:~/project/module/blog/site/moderate/i18n/dictionary.config.php}',
    'project.site.blog.moderate.all' => '{#lazy:~/project/module/blog/site/moderate/all/i18n/dictionary.config.php}',
    'project.site.blog.moderate.edit' => '{#lazy:~/project/module/blog/site/moderate/edit/i18n/dictionary.config.php}',
    'project.site.blog.moderate.own' => '{#lazy:~/project/module/blog/site/moderate/own/i18n/dictionary.config.php}',
    'project.site.blog.reject' => '{#lazy:~/project/module/blog/site/reject/i18n/dictionary.config.php}',
    'project.site.blog.reject.edit' => '{#lazy:~/project/module/blog/site/reject/edit/i18n/dictionary.config.php}',
    'project.site.blog.reject.view' => '{#lazy:~/project/module/blog/site/reject/view/i18n/dictionary.config.php}',
    'project.site.blog.category' => '{#lazy:~/project/module/blog/site/category/i18n/dictionary.config.php}',
    'project.site.blog.author' => '{#lazy:~/project/module/blog/site/author/i18n/dictionary.config.php}',
    'project.site.blog.author.profile' => '{#lazy:~/project/module/blog/site/author/profile/i18n/dictionary.config.php}',
    'project.site.blog.author.view' => '{#lazy:~/project/module/blog/site/author/view/i18n/dictionary.config.php}',
    'project.site.blog.tag' => '{#lazy:~/project/module/blog/site/tag/i18n/dictionary.config.php}',

    'project.site.search' => '{#lazy:~/project/module/search/site/i18n/dictionary.config.php}',
    'project.site.surveys' => '{#lazy:~/project/module/surveys/site/i18n/dictionary.config.php}',

    'collection' => [

        'en-US' => [
            'en-US' => 'English',
            'ru-RU' => 'Русский',

            ICmsObject::FIELD_CREATED => 'Creation date',
            ICmsObject::FIELD_DISPLAY_NAME => 'Display name',
            ICmsObject::FIELD_UPDATED => 'Update date',
            ICmsObject::FIELD_GUID => 'GUID',
            ICmsObject::FIELD_IDENTIFY => 'Identifier',
            ICmsObject::FIELD_TYPE => 'Type',
            ICmsObject::FIELD_VERSION => 'Version',
            
            IRecyclableObject::FIELD_TRASHED => 'Trashed',
            ILockedAccessibleObject::FIELD_LOCKED => 'Locked',
            IActiveAccessibleObject::FIELD_ACTIVE => 'Active',

            'Default or inherited layout' => 'Default or inherited layout',

            CmsHierarchicObject::FIELD_CHILDREN => 'Children',
            CmsHierarchicObject::FIELD_HIERARCHY_LEVEL => 'Hierarchy level',
            CmsHierarchicObject::FIELD_MPATH => 'Materialized path',
            CmsHierarchicObject::FIELD_ORDER => 'Hierarchy order',
            CmsHierarchicObject::FIELD_PARENT => 'Parent',
            CmsHierarchicObject::FIELD_URI => 'URI',

            ICmsPage::FIELD_PAGE_CONTENTS => 'Contents',
            ICmsPage::FIELD_PAGE_H1 => 'H1',
            ICmsPage::FIELD_PAGE_LAYOUT => 'Layout',
            ICmsPage::FIELD_PAGE_META_DESCRIPTION => 'Meta description',
            ICmsPage::FIELD_PAGE_META_KEYWORDS => 'Meta keywords',
            ICmsPage::FIELD_PAGE_META_TITLE => 'Meta title',
            ICmsPage::FIELD_PAGE_SLUG => 'Slug'
        ],

        'ru-RU' => [
            'en-US' => 'English',
            'ru-RU' => 'Русский',

            ICmsObject::FIELD_CREATED => 'Дата создания',
            ICmsObject::FIELD_DISPLAY_NAME => 'Название',
            ICmsObject::FIELD_UPDATED => 'Дата последнего обновления',
            ICmsObject::FIELD_GUID => 'GUID',
            ICmsObject::FIELD_IDENTIFY => 'Идентификатор',
            ICmsObject::FIELD_TYPE => 'Тип',
            ICmsObject::FIELD_VERSION => 'Версия',

            CmsHierarchicObject::FIELD_CHILDREN => 'Дочерние сущности',
            CmsHierarchicObject::FIELD_HIERARCHY_LEVEL => 'Уровень вложенности в иерархии',
            CmsHierarchicObject::FIELD_MPATH => 'Материализованный путь',
            CmsHierarchicObject::FIELD_ORDER => 'Порядок в иерархии',
            CmsHierarchicObject::FIELD_PARENT => 'Родительская сущность',
            CmsHierarchicObject::FIELD_URI => 'URI',

            ICmsPage::FIELD_PAGE_CONTENTS => 'Контент',
            ICmsPage::FIELD_PAGE_H1 => 'H1',
            ICmsPage::FIELD_PAGE_LAYOUT => 'Шаблон',
            ICmsPage::FIELD_PAGE_META_DESCRIPTION => 'Meta description',
            ICmsPage::FIELD_PAGE_META_KEYWORDS => 'Meta keywords',
            ICmsPage::FIELD_PAGE_META_TITLE => 'Meta title',
            ICmsPage::FIELD_PAGE_SLUG => 'Псевдостатический адрес',

            IRecyclableObject::FIELD_TRASHED => 'В корзине',
            ILockedAccessibleObject::FIELD_LOCKED => 'Заблокировано',
            IActiveAccessibleObject::FIELD_ACTIVE => 'Активность',

            'Default or inherited layout' => 'По умолчанию или унаследованный',
        ]
    ],

    'form' => [
        'en-US' => [
            'common' => 'Common',
            'meta' => 'Meta',
            'importSetting' => 'Import setting',
            'contents' => 'Contents',
            'Save' => 'Save',
            'Add' => 'Add',
            'Change' => 'Change',
            'Send request' => 'Send request',
            'Captcha' => 'Captcha',

            'Value is required.' => 'Value is required.',
            'Invalid csrf token.' => 'Invalid csrf token.',
            'Invalid captcha test.' => 'Invalid captcha test.',
        ],
        'ru-RU' => [
            'common' => 'Общее',
            'meta' => 'Мета-информация',
            'importSetting' => 'Настройки импорта',
            'contents' => 'Контент',
            'Save' => 'Сохранить',
            'Add' => 'Добавить',
            'Change' => 'Изменить',
            'Send request' => 'Отправить запрос',
            'Captcha' => 'Введите код с картинки',

            'Value is required.' => 'Значение поля обязательно для заполнения.',
            'Invalid csrf token.' => 'Недопустимый маркер CSRF.',
            'Invalid captcha test.' => 'Неверный код с картинки.',
        ]
    ],

    'collection.user' => '{#lazy:~/project/module/users/configuration/user/i18n/dictionary.config.php}',
    'collection.userGroup' => '{#lazy:~/project/module/users/configuration/group/i18n/dictionary.config.php}',
    'collection.userUserGroup' => '{#lazy:~/project/module/users/configuration/usergroup/i18n/dictionary.config.php}',

    'collection.newsItem' => '{#lazy:~/project/module/news/configuration/item/i18n/dictionary.config.php}',
    'collection.newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/i18n/dictionary.config.php}',
    'collection.newsSubject' => '{#lazy:~/project/module/news/configuration/subject/i18n/dictionary.config.php}',
    'collection.newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/i18n/dictionary.config.php}',
    'collection.rssScenarioSubject' => '{#lazy:~/project/module/news/configuration/rsssubject/i18n/dictionary.config.php}',
    'collection.newsItemSubject' => '{#lazy:~/project/module/news/configuration/itemsubject/i18n/dictionary.config.php}',

    'collection.blogCategory' => '{#lazy:~/project/module/blog/configuration/category/i18n/dictionary.config.php}',
    'collection.blogPost' => '{#lazy:~/project/module/blog/configuration/post/i18n/dictionary.config.php}',
    'collection.blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/i18n/dictionary.config.php}',
    'collection.blogComment' => '{#lazy:~/project/module/blog/configuration/comment/i18n/dictionary.config.php}',
    'collection.blogTag' => '{#lazy:~/project/module/blog/configuration/tag/i18n/dictionary.config.php}',
    'collection.blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/i18n/dictionary.config.php}',
    'collection.blogPostTag' => '{#lazy:~/project/module/blog/configuration/posttag/i18n/dictionary.config.php}',
    'collection.rssBlogTag' => '{#lazy:~/project/module/blog/configuration/rsstag/i18n/dictionary.config.php}',
    'collection.blogPostStatus' => '{#lazy:~/project/module/blog/configuration/poststatus/i18n/dictionary.config.php}',
    'collection.blogCommentStatus' => '{#lazy:~/project/module/blog/configuration/commentstatus/i18n/dictionary.config.php}',

    'collection.layout' => '{#lazy:~/project/module/structure/configuration/layout/i18n/dictionary.config.php}',
    'collection.structure' => '{#lazy:~/project/module/structure/configuration/structure/i18n/dictionary.config.php}',
    'collection.infoblock' => '{#lazy:~/project/module/structure/configuration/infoblock/i18n/dictionary.config.php}',
    'collection.menu' => '{#lazy:~/project/module/structure/configuration/menu/i18n/dictionary.config.php}',
    'collection.robots' => '{#lazy:~/project/module/seo/configuration/robots/i18n/dictionary.config.php}',

    'collection.survey' => '{#lazy:~/project/module/surveys/configuration/survey/i18n/dictionary.config.php}',
    'collection.answer' => '{#lazy:~/project/module/surveys/configuration/answer/i18n/dictionary.config.php}',

    'collection.searchIndex' => '{#lazy:~/project/module/search/configuration/index/i18n/dictionary.config.php}',

    'collection.serviceBackup' => '{#lazy:~/project/module/service/configuration/backup/i18n/dictionary.config.php}',
];