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

        'project.admin.api' => '{#lazy:~/project/admin/api/i18n/dictionary.config.php}',

        'project.admin.api.news' => '{#lazy:~/project/module/news/admin/i18n/dictionary.config.php}',
        'project.admin.api.news.item' => '{#lazy:~/project/module/news/admin/item/i18n/dictionary.config.php}',
        'project.admin.api.news.rubric' => '{#lazy:~/project/module/news/admin/rubric/i18n/dictionary.config.php}',
        'project.admin.api.news.subject' => '{#lazy:~/project/module/news/admin/subject/i18n/dictionary.config.php}',
        'project.admin.api.news.rss' => '{#lazy:~/project/module/news/admin/rss/i18n/dictionary.config.php}',

        'project.admin.api.files' => '{#lazy:~/project/module/files/admin/i18n/dictionary.config.php}',
        'project.admin.api.files.manager' => '{#lazy:~/project/module/files/admin/manager/i18n/dictionary.config.php}',

        'project.admin.api.users' => '{#lazy:~/project/module/users/admin/i18n/dictionary.config.php}',
        'project.admin.api.users.user' => '{#lazy:~/project/module/users/admin/user/i18n/dictionary.config.php}',
        'project.admin.api.users.group' => '{#lazy:~/project/module/users/admin/group/i18n/dictionary.config.php}',

        'project.admin.api.structure' => '{#lazy:~/project/module/structure/admin/i18n/dictionary.config.php}',
        'project.admin.api.structure.page' => '{#lazy:~/project/module/structure/admin/page/i18n/dictionary.config.php}',
        'project.admin.api.structure.layout' => '{#lazy:~/project/module/structure/admin/layout/i18n/dictionary.config.php}',
        'project.admin.api.structure.infoblock' => '{#lazy:~/project/module/structure/admin/infoblock/i18n/dictionary.config.php}',

        'project.admin.api.statistics' => '{#lazy:~/project/module/statistics/admin/i18n/dictionary.config.php}',
        'project.admin.api.statistics.metrika' => '{#lazy:~/project/module/statistics/admin/metrika/i18n/dictionary.config.php}',

        'project.admin.api.service' => '{#lazy:~/project/module/service/admin/i18n/dictionary.config.php}',
        'project.admin.api.service.backup' => '{#lazy:~/project/module/service/admin/backup/i18n/dictionary.config.php}',

        'project.admin.api.seo' => '{#lazy:~/project/module/seo/admin/i18n/dictionary.config.php}',
        'project.admin.api.seo.megaindex' => '{#lazy:~/project/module/seo/admin/megaindex/i18n/dictionary.config.php}',
        'project.admin.api.seo.yandex' => '{#lazy:~/project/module/seo/admin/yandex/i18n/dictionary.config.php}',

        'project.admin.api.search' => '{#lazy:~/project/module/search/admin/i18n/dictionary.config.php}',

        'project.admin.api.blog' => '{#lazy:~/project/module/blog/admin/i18n/dictionary.config.php}',

        'project.admin.api.models' => '{#lazy:~/project/module/models/admin/i18n/dictionary.config.php}',


        'project.admin.settings' => '{#lazy:~/project/admin/settings/i18n/dictionary.config.php}',
        'project.admin.settings.site' => '{#lazy:~/project/site/settings/i18n/dictionary.config.php}',
        'project.admin.settings.service' => '{#lazy:~/project/module/service/settings/i18n/dictionary.config.php}',
        'project.admin.settings.service.backup' => '{#lazy:~/project/module/service/settings/backup/i18n/dictionary.config.php}',
        'project.admin.settings.seo' => '{#lazy:~/project/module/seo/settings/i18n/dictionary.config.php}',
        'project.admin.settings.seo.megaindex' => '{#lazy:~/project/module/seo/settings/megaindex/i18n/dictionary.config.php}',
        'project.admin.settings.seo.yandex' => '{#lazy:~/project/module/seo/settings/yandex/i18n/dictionary.config.php}',
        'project.admin.settings.statistics' => '{#lazy:~/project/module/statistics/settings/i18n/dictionary.config.php}',
        'project.admin.settings.statistics.metrika' => '{#lazy:~/project/module/statistics/settings/metrika/i18n/dictionary.config.php}',
        'project.admin.settings.users' => '{#lazy:~/project/module/users/settings/i18n/dictionary.config.php}',

        'project.admin.settings.forms' => '{#lazy:~/project/module/forms/settings/i18n/dictionary.config.php}',
        'project.admin.settings.forms.captcha' => '{#lazy:~/project/module/forms/settings/captcha/i18n/dictionary.config.php}',

        'project.site.users' => '{#lazy:~/project/module/users/site/i18n/dictionary.config.php}',
        'project.site.users.authorization' => '{#lazy:~/project/module/users/site/authorization/i18n/dictionary.config.php}',
        'project.site.users.registration' => '{#lazy:~/project/module/users/site/registration/i18n/dictionary.config.php}',
        'project.site.users.registration.activation' => '{#lazy:~/project/module/users/site/registration/activation/i18n/dictionary.config.php}',
        'project.site.users.restoration' => '{#lazy:~/project/module/users/site/restoration/i18n/dictionary.config.php}',
        'project.site.users.restoration.confirmation' => '{#lazy:~/project/module/users/site/restoration/confirmation/i18n/dictionary.config.php}',
        'project.site.users.profile' => '{#lazy:~/project/module/users/site/profile/i18n/dictionary.config.php}',
        'project.site.users.profile.password' => '{#lazy:~/project/module/users/site/profile/password/i18n/dictionary.config.php}',

        'project.site.blog.comment' => '{#lazy:~/project/module/blog/site/comment/i18n/dictionary.config.php}',
        'project.site.blog.post' => '{#lazy:~/project/module/blog/site/post/i18n/dictionary.config.php}',

        'collection' => [

            'en-US' => [
                ICmsObject::FIELD_CREATED => 'Creation date',
                ICmsObject::FIELD_DISPLAY_NAME => 'Display name',
                ICmsObject::FIELD_UPDATED => 'Update date',
                ICmsObject::FIELD_GUID => 'GUID',
                ICmsObject::FIELD_IDENTIFY => 'Identifier',
                ICmsObject::FIELD_TYPE => 'Type',
                ICmsObject::FIELD_VERSION => 'Version',

                CmsHierarchicObject::FIELD_CHILDREN => 'Children',
                CmsHierarchicObject::FIELD_CHILD_COUNT => 'Child count',
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
                ICmsPage::FIELD_PAGE_SLUG => 'Slug',

                IRecyclableObject::FIELD_TRASHED => 'Trashed',
                ILockedAccessibleObject::FIELD_LOCKED => 'Locked',
                IActiveAccessibleObject::FIELD_ACTIVE => 'Active'

            ],

            'ru-RU' => [
                ICmsObject::FIELD_CREATED => 'Дата создания',
                ICmsObject::FIELD_DISPLAY_NAME => 'Имя отображения',
                ICmsObject::FIELD_UPDATED => 'Дата последнего обновления',
                ICmsObject::FIELD_GUID => 'GUID',
                ICmsObject::FIELD_IDENTIFY => 'Идентификатор',
                ICmsObject::FIELD_TYPE => 'Тип',
                ICmsObject::FIELD_VERSION => 'Версия',

                CmsHierarchicObject::FIELD_CHILDREN => 'Дочерние сущности',
                CmsHierarchicObject::FIELD_CHILD_COUNT => 'Количество дочерних сущностей',
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
                'Send request' => 'Send request',
            ],
            'ru-RU' => [
                'common' => 'Общее',
                'meta' => 'Мета-информация',
                'importSetting' => 'Настройки импорта',
                'contents' => 'Контент',

                'Save' => 'Сохранить',
                'Add' => 'Добавить',
                'Send request' => 'Отправить запрос',
            ]
        ],

        'collection.user' => '{#lazy:~/project/module/users/configuration/user/i18n/dictionary.config.php}',
        'collection.userGroup' => '{#lazy:~/project/module/users/configuration/group/i18n/dictionary.config.php}',

        'collection.newsItem' => '{#lazy:~/project/module/news/configuration/item/i18n/dictionary.config.php}',
        'collection.newsRubric' => '{#lazy:~/project/module/news/configuration/rubric/i18n/dictionary.config.php}',
        'collection.newsSubject' => '{#lazy:~/project/module/news/configuration/subject/i18n/dictionary.config.php}',
        'collection.newsRssImportScenario' => '{#lazy:~/project/module/news/configuration/rss/i18n/dictionary.config.php}',

        'collection.blogCategory' => '{#lazy:~/project/module/blog/configuration/category/i18n/dictionary.config.php}',
        'collection.blogPost' => '{#lazy:~/project/module/blog/configuration/post/i18n/dictionary.config.php}',
        'collection.blogAuthor' => '{#lazy:~/project/module/blog/configuration/author/i18n/dictionary.config.php}',
        'collection.blogComment' => '{#lazy:~/project/module/blog/configuration/comment/i18n/dictionary.config.php}',
        'collection.blogTag' => '{#lazy:~/project/module/blog/configuration/tag/i18n/dictionary.config.php}',
        'collection.blogRssImportScenario' => '{#lazy:~/project/module/blog/configuration/rss/i18n/dictionary.config.php}',

        'collection.layout' => '{#lazy:~/project/module/structure/configuration/layout/i18n/dictionary.config.php}',
        'collection.structure' => '{#lazy:~/project/module/structure/configuration/structure/i18n/dictionary.config.php}',
        'collection.infoblock' => '{#lazy:~/project/module/structure/configuration/infoblock/i18n/dictionary.config.php}',
    ];