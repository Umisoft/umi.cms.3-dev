<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace project\install\controller;

use umi\dbal\cluster\IDbCluster;
use umi\dbal\driver\IDialect;
use umi\hmvc\controller\BaseController;
use umi\http\Response;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\collection\SimpleCollection;
use umi\orm\collection\SimpleHierarchicCollection;
use umi\orm\collection\TCollectionManagerAware;
use umi\orm\manager\IObjectManagerAware;
use umi\orm\manager\TObjectManagerAware;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\exception\InvalidObjectsException;
use umicms\exception\RuntimeException;
use umicms\module\IModuleAware;
use umicms\module\TModuleAware;
use umicms\orm\collection\behaviour\IRecoverableCollection;
use umicms\orm\dump\ICmsObjectDumpAware;
use umicms\orm\dump\TCmsObjectDumpAware;
use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\ICmsObject;
use umicms\project\module\blog\model\collection\BlogCommentCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\module\news\model\collection\NewsRssImportScenarioCollection;
use umicms\project\module\news\model\NewsModule;
use umicms\project\module\search\model\SearchApi;
use umicms\project\module\search\model\SearchIndexApi;
use umicms\project\module\search\model\SearchModule;
use umicms\project\module\structure\model\object\InfoBlock;
use umicms\project\module\structure\model\object\Menu;
use umicms\project\module\structure\model\object\MenuExternalItem;
use umicms\project\module\structure\model\object\MenuInternalItem;
use umicms\project\module\structure\model\object\StaticPage;
use umicms\project\module\structure\model\object\StructureElement;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\Supervisor;
use umicms\project\module\users\model\object\UserGroup;
use umicms\project\module\users\model\UsersModule;

/**
 * Class InstallController
 */
class InstallController extends BaseController implements ICmsObjectDumpAware, ICollectionManagerAware, IObjectPersisterAware, IObjectManagerAware, IModuleAware
{

    use TCollectionManagerAware;
    use TObjectPersisterAware;
    use TObjectManagerAware;
    use TModuleAware;
    use TCmsObjectDumpAware;

    /**
     * @var IDbCluster $dbCluster
     */
    protected $dbCluster;
    /**
     * @var UsersModule $usersModule
     */
    protected $usersModule;
    /**
     * @var string $testLayout
     */
    protected $blogLayout;
    /**
     * @var SearchApi $searchApi
     */
    protected $backupRepository;
    /**
     * @var SearchIndexApi $searchIndexApi
     */
    private $searchIndexApi;
    /**
     * @var RegisteredUser $userSv
     */
    private $userSv;
    /**
     * @var RegisteredUser $user
     */
    private $user;

    public function __construct(IDbCluster $dbCluster, SearchModule $searchModule, UsersModule $usersModule)
    {
        $this->dbCluster = $dbCluster;
        $this->usersModule = $usersModule;
        $this->searchIndexApi = $searchModule->getSearchApi();
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        header('Content-type: text/plain');


        $connection = $this->dbCluster->getConnection();
        /**
         * @var IDialect $dialect
         */
        $dialect = $connection->getDatabasePlatform();

        $connection->exec($dialect->getDisableForeignKeysSQL());

        $this->dropTables();

        try {
            echo "Sync table schemes...\n";
            foreach ($this->getModules() as $module) {
                $module->getModels()->syncAllSchemes();
            }

            echo "Installing structure...\n";
            $this->installStructure();
            echo "Installing users...\n";
            $this->installUsers();
            echo "Installing news...\n";
            $this->installNews();
            echo "Installing gratitude...\n";
            $this->installGratitude();
            echo "Installing blog...\n";
            $this->installBlog();

            $this->commit();
            $this->getObjectManager()->unloadObjects();

            //$this->installSearch();
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            while ($e = $e->getPrevious()) {
                echo '... ' . $e->getMessage() . "\n";
            }
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 100);
        }

        exit('Done');
    }

    protected function installUsers()
    {
        /**
         * @var SimpleCollection $userCollection
         */
        $userCollection = $this->getCollectionManager()->getCollection('user');
        /**
         * @var SimpleCollection $groupCollection
         */
        $groupCollection = $this->getCollectionManager()->getCollection('userGroup');

        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');

        $usersPage = $structureCollection->add('users', 'system')
            ->setValue('displayName', 'Пользователи')
            ->setValue('metaTitle', 'Пользователи')
            ->setValue('h1', 'Пользователи');

        $usersPage->getProperty('componentName')->setValue('users');
        $usersPage->getProperty('componentPath')->setValue('users');

        $authorizationPage = $structureCollection->add('auth', 'system', $usersPage)
            ->setValue('displayName', 'Авторизация')
            ->setValue('metaTitle', 'Авторизация')
            ->setValue('h1', 'Авторизация');

        $authorizationPage->getProperty('componentName')->setValue('authorization');
        $authorizationPage->getProperty('componentPath')->setValue('users.authorization');

        $registrationPage = $structureCollection->add('registration', 'system', $usersPage)
            ->setValue('displayName', 'Регистрация')
            ->setValue('metaTitle', 'Регистрация')
            ->setValue('h1', 'Регистрация');

        $registrationPage->getProperty('componentName')->setValue('registration');
        $registrationPage->getProperty('componentPath')->setValue('users.registration');

        $activationPage = $structureCollection->add('activate', 'system', $registrationPage)
            ->setValue('displayName', 'Активация')
            ->setValue('metaTitle', 'Активация')
            ->setValue('h1', 'Активация');

        $activationPage->getProperty('componentName')->setValue('activation');
        $activationPage->getProperty('componentPath')->setValue('users.registration.activation');

        $resetPasswordRequestPage = $structureCollection->add('restore', 'system', $usersPage)
            ->setValue('displayName', 'Запрос смены пароля')
            ->setValue('metaTitle', 'Запрос смены пароля')
            ->setValue('h1', 'Запрос смены пароля');

        $resetPasswordRequestPage->getProperty('componentName')->setValue('restoration');
        $resetPasswordRequestPage->getProperty('componentPath')->setValue('users.restoration');

        $restPasswordConfirmationPage = $structureCollection->add('confirm', 'system', $resetPasswordRequestPage)
            ->setValue('displayName', 'Подтверждение смены пароля')
            ->setValue('metaTitle', 'Подтверждение смены пароля')
            ->setValue('h1', 'Подтверждение смены пароля');

        $restPasswordConfirmationPage->getProperty('componentName')->setValue('confirmation');
        $restPasswordConfirmationPage->getProperty('componentPath')->setValue('users.restoration.confirmation');

        $profilePage = $structureCollection->add('profile', 'system', $usersPage)
            ->setValue('displayName', 'Профиль')
            ->setValue('metaTitle', 'Профиль')
            ->setValue('h1', 'Профиль');

        $profilePage->getProperty('componentName')->setValue('profile');
        $profilePage->getProperty('componentPath')->setValue('users.profile');

        $passwordChangePage = $structureCollection->add('pass', 'system', $profilePage)
            ->setValue('displayName', 'Смена пароля')
            ->setValue('metaTitle', 'Смена пароля')
            ->setValue('h1', 'Смена пароля');

        $passwordChangePage->getProperty('componentName')->setValue('password');
        $passwordChangePage->getProperty('componentPath')->setValue('users.profile.password');


        /**
         * @var UserGroup $visitors
         */
        $visitors = $groupCollection->add()
            ->setValue('displayName', 'Посетители')
            ->setValue('displayName', 'Visitors', 'en-US')
            ->setGUID('bedcbbac-7dd1-4b60-979a-f7d944ecb08a');
        $visitors->getProperty('locked')->setValue(true);

        $visitors->roles = [
            'project' => ['siteExecutor', 'adminExecutor'],
            'project.admin' => ['viewer', 'restExecutor'],
            'project.admin.rest' => ['viewer'],

            'project.site' => [
                'usersExecutor',
                'newsExecutor',
                'structureExecutor',
                'blogExecutor',
                'searchExecutor',
                'viewer',
                'widgetExecutor'
            ],

            'project.site.users' => [
                'authorizationExecutor',
                'registrationExecutor',
                'restorationExecutor',
                'viewer'
            ],
            'project.site.users.authorization' => ['viewer'],
            'project.site.users.registration' => ['activationExecutor', 'viewer'],
            'project.site.users.registration.activation' => ['viewer'],
            'project.site.users.restoration' => ['confirmationExecutor', 'viewer'],
            'project.site.users.restoration.confirmation' => ['viewer'],

            'project.site.structure' => [
                'menuExecutor',
                'infoblockExecutor',
                'viewer'
            ],
            'project.site.structure.menu' => ['viewer'],
            'project.site.structure.infoblock' => ['viewer'],

            'project.site.news' => [
                'itemExecutor',
                'rubricExecutor',
                'subjectExecutor',
                'viewer'
            ],
            'project.site.news.item' => ['viewer', 'rssViewer'],
            'project.site.news.rubric' => ['viewer', 'rssViewer'],
            'project.site.news.subject' => ['viewer', 'rssViewer'],

            'project.site.blog' => [
                'categoryExecutor',
                'postExecutor',
                'tagExecutor',
                'authorExecutor',
                'commentExecutor',
                'viewer'
            ],

            'project.site.blog.post' => [
                'viewExecutor',
                'viewer',
                'rssViewer'
            ],
            'project.site.blog.post.view' => ['viewer'],

            'project.site.blog.category' => ['viewer', 'rssViewer'],
            'project.site.blog.tag' => ['viewer', 'rssViewer'],
            'project.site.blog.author' => [
                'viewExecutor',
                'viewer',
                'rssViewer'
            ],
            'project.site.blog.author.view' => ['viewer', 'rssViewer'],

            'project.site.blog.comment' => ['viewer']
        ];

        /**
         * @var UserGroup $registeredUsers
         */
        $registeredUsers = $groupCollection->add()
            ->setValue('displayName', 'Зaрегистрированные пользователи')
            ->setValue('displayName', 'Registered users', 'en-US')
            ->setGUID('daabebf8-f3b3-4f62-a23d-522eff9b7f68');
        $registeredUsers->getProperty('locked')->setValue(true);

        $registeredUsers->roles = [
            'project.site.users' => ['profileExecutor', 'viewer'],
            'project.site.users.profile' => ['passwordExecutor', 'viewer'],
            'project.site.users.profile.password' => ['viewer'],
        ];

        /**
         * @var UserGroup $authorsWithPremoderation
         */
        $authorsWithPremoderation = $groupCollection->add()
            ->setValue('displayName', 'Авторы с премодерацией постов')
            ->setValue('displayName', 'Authors with premoderation', 'en-US');

        $authorsWithPremoderation->roles = [
            'project.site.blog' => [
                'draftExecutor',
                'moderateExecutor',
                'rejectExecutor'
            ],

            'project.site.blog.draft' => [
                'editExecutor',
                'viewExecutor',
                'viewer',
                'author'
            ],
            'project.site.blog.draft.edit' => ['author'],
            'project.site.blog.draft.view' => ['viewer'],

            'project.site.blog.moderate' => [
                'editExecutor',
                'ownExecutor',
                'viewer',
            ],
            'project.site.blog.moderate.edit' => ['author'],
            'project.site.blog.moderate.own' => ['viewer'],

            'project.site.blog.post' => [
                'addExecutor',
                'editExecutor',
                'viewExecutor',
                'viewer',
                'author'
            ],
            'project.site.blog.post.add' => ['author'],

            'project.site.blog.reject' => [
                'editExecutor',
                'viewExecutor',
                'viewer',
                'author'
            ],
            'project.site.blog.reject.edit' => ['author'],
            'project.site.blog.reject.view' => ['viewer', 'author'],

            'project.site.blog.author' => ['profileExecutor'],
            'project.site.blog.author.profile' => ['author'],
        ];

        /**
         * @var UserGroup $authorsWithoutPremoderation
         */
        $authorsWithoutPremoderation = $groupCollection->add()
            ->setValue('displayName', 'Авторы без премодерации постов')
            ->setValue('displayName', 'Authors without premoderation', 'en-US');

        $authorsWithoutPremoderation->roles = [
            'project.site.blog' => [
                'draftExecutor',
                'moderateExecutor',
                'rejectExecutor'
            ],

            'project.site.blog.draft' => [
                'editExecutor',
                'viewExecutor',
                'viewer',
                'publisher'
            ],
            'project.site.blog.draft.edit' => ['author'],
            'project.site.blog.draft.view' => ['viewer'],

            'project.site.blog.moderate' => [
                'editExecutor',
                'viewExecutor',
                'viewer',
            ],
            'project.site.blog.moderate.edit' => ['author'],
            'project.site.blog.moderate.own' => ['viewer'],

            'project.site.blog.post' => [
                'addExecutor',
                'editExecutor',
                'viewExecutor',
                'viewer',
                'author'
            ],
            'project.site.blog.post.add' => ['author'],

            'project.site.blog.reject' => [
                'editExecutor',
                'viewExecutor',
                'viewer',
                'author'
            ],
            'project.site.blog.reject.edit' => ['author'],
            'project.site.blog.reject.view' => ['viewer', 'author'],

            'project.site.blog.author.profile' => ['author'],
        ];

        /**
         * @var UserGroup $commentWithoutPremoderation
         */
        $commentWithoutPremoderation = $groupCollection->add()
            ->setValue('displayName', 'Комментарии без премодерацией')
            ->setValue('displayName', 'Comment without premoderation', 'en-US');

        $commentWithoutPremoderation->roles = [
            'project.site.blog.comment' => [
                'addExecutor'
            ],
            'project.site.blog.comment.add' => ['commentator']
        ];

        /**
         * @var UserGroup $commentWithPremoderation
         */
        $commentWithPremoderation = $groupCollection->add()
            ->setValue('displayName', 'Комментарии с премодерацией')
            ->setValue('displayName', 'Comment with premoderation', 'en-US');

        $commentWithPremoderation->roles = [
            'project.site.blog.comment' => [
                'addExecutor'
            ],
            'project.site.blog.comment.add' => ['commentatorPremoderation']
        ];

        /**
         * @var UserGroup $moderator
         */
        $moderator = $groupCollection->add()
            ->setValue('displayName', 'Модератор')
            ->setValue('displayName', 'Moderator', 'en-US');

        $moderator->roles = [
            'project.site.blog' => [
                'draftExecutor',
                'moderateExecutor',
                'rejectExecutor'
            ],

            'project.site.blog.comment' => ['moderator'],

            'project.site.blog.moderate' => [
                'editExecutor',
                'ownExecutor',
                'allExecutor',
                'moderator'
            ],
            'project.site.blog.moderate.edit' => ['moderator'],
            'project.site.blog.moderate.all' => ['viewer'],

            'project.site.blog.post' => [
                'addExecutor',
                'editExecutor',
                'viewExecutor',
                'moderator'
            ],
            'project.site.blog.post.edit' => ['moderator'],
        ];

        /**
         * @var UserGroup $administrators
         */
        $administrators = $groupCollection->add()
            ->setValue('displayName', 'Администраторы')
            ->setValue('displayName', 'Administrator', 'en-US');
        $administrators->roles = [
            'project.admin.rest' => ['newsExecutor', 'structureExecutor', 'usersExecutor'],

            'project.admin.rest.news' => ['rubricExecutor', 'itemExecutor', 'subjectExecutor'],
            'project.admin.rest.news.item' => ['editor'],
            'project.admin.rest.news.rubric' => ['editor'],
            'project.admin.rest.news.subject' => ['editor'],

            'project.admin.rest.structure' => ['pageExecutor', 'layoutExecutor'],
            'project.admin.rest.structure.page' => ['editor'],
            'project.admin.rest.structure.layout' => ['editor'],

            'project.admin.rest.users' => ['userExecutor'],
            'project.admin.rest.users.user' => ['editor'],
        ];

        /**
         * @var Supervisor $sv
         */
        $sv = $userCollection->add(Supervisor::TYPE_NAME)
            ->setValue('displayName', 'Супервайзер')
            ->setValue('displayName', 'Supervisor', 'en-US')
            ->setValue('login', 'sv')
            ->setValue('firstName', 'Супервайзер')
            ->setValue('email', 'sv@umisoft.ru')
            ->setGUID('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');
        $sv->getProperty('locked')->setValue(true);

        $sv->setPassword('1');

        $this->userSv = $sv;

        /**
         * @var RegisteredUser $admin
         */
        $admin = $userCollection->add(RegisteredUser::TYPE_NAME)
            ->setValue('displayName', 'Администратор')
            ->setValue('displayName', 'Administrator', 'en-US')
            ->setValue('firstName', 'Администратор')
            ->setValue('login', 'admin')
            ->setValue('email', 'admin@umisoft.ru');

        $admin->groups->attach($visitors);
        $admin->groups->attach($registeredUsers);
        $admin->groups->attach($administrators);
        $admin->groups->attach($moderator);
        $admin->setPassword('admin');

        /**
         * @var RegisteredUser $user
         */
        $user = $userCollection->add(RegisteredUser::TYPE_NAME)
            ->setValue('displayName', 'Зарегистрированный пользователь')
            ->setValue('firstName', 'Зарегистрированный пользователь')
            ->setValue('login', 'demo')
            ->setValue('email', 'demo@umisoft.ru');

        $user->groups->attach($visitors);
        $user->groups->attach($authorsWithPremoderation);
        $user->groups->attach($commentWithPremoderation);
        $user->groups->attach($registeredUsers);
        $user->setPassword('demo');

        $this->user = $user;

        /**
         * @var Guest $guest
         */
        $guest = $userCollection->add(Guest::TYPE_NAME)
            ->setValue('displayName', 'Гость')
            ->setValue('displayName', 'Guest', 'en-US')
            ->setGUID('552802d2-278c-46c2-9525-cd464bbed63e');
        $guest->getProperty('locked')->setValue(true);

        $guest->groups->attach($visitors);
        $guest->groups->attach($commentWithPremoderation);

    }

    protected function installBlog()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var SimpleHierarchicCollection $categoryCollection
         */
        $categoryCollection = $this->getCollectionManager()->getCollection('blogCategory');
        /**
         * @var BlogPostCollection $postCollection
         */
        $postCollection = $this->getCollectionManager()->getCollection('blogPost');
        /**
         * @var SimpleCollection $authorCollection
         */
        $authorCollection = $this->getCollectionManager()->getCollection('blogAuthor');
        /**
         * @var BlogCommentCollection $commentCollection
         */
        $commentCollection = $this->getCollectionManager()->getCollection('blogComment');
        /**
         * @var SimpleCollection $tagCollection
         */
        $tagCollection = $this->getCollectionManager()->getCollection('blogTag');
        /**
         * @var SimpleCollection $rssScenarioCollection
         */
        $rssScenarioCollection = $this->getCollectionManager()->getCollection('blogRssImportScenario');
        /** @var SimpleCollection $userCollection */
        $userCollection = $this->getCollectionManager()->getCollection('user');


        $blogPage = $structureCollection->add('blogik', 'system')
            ->setValue('displayName', 'Блог')
            ->setValue('displayName', 'Blog', 'en-US')
            ->setValue('h1', 'Блог')
            ->setGUID('e6b89f38-7af3-4bda-80fd-3d5a4cf080cf')
            ->setValue('inMenu', true)
            ->setValue('layout', $this->blogLayout);

        $blogPage->getProperty('locked')->setValue(true);
        $blogPage->getProperty('componentName')->setValue('blog');
        $blogPage->getProperty('componentPath')->setValue('blog');

        $category = $structureCollection->add('kategorii', 'system', $blogPage)
            ->setValue('displayName', 'Категория блога')
            ->setValue('displayName', 'Category', 'en-US')
            ->setGUID('29449a5c-e0b0-42ad-9f1c-3d015540b024');

        $category->getProperty('locked')->setValue(true);
        $category->getProperty('componentName')->setValue('category');
        $category->getProperty('componentPath')->setValue('blog.category');

        $tag = $structureCollection->add('blogtag', 'system', $blogPage)
            ->setValue('displayName', 'Тэг блога')
            ->setValue('displayName', 'Tag', 'en-US')
            ->setGUID('3fa39832-9239-48a5-a82a-1dd2fcd0f042');

        $tag->getProperty('locked')->setValue(true);
        $tag->getProperty('componentName')->setValue('tag');
        $tag->getProperty('componentPath')->setValue('blog.tag');

        $post = $structureCollection->add('post', 'system', $blogPage)
            ->setValue('displayName', 'Пост блога')
            ->setValue('displayName', 'Post', 'en-US')
            ->setGUID('257fb155-9fbf-4b99-8b1c-c0ae179070ca');
        $post->getProperty('locked')->setValue(true);
        $post->getProperty('componentName')->setValue('post');
        $post->getProperty('componentPath')->setValue('blog.post');

        $addPost = $structureCollection->add('add', 'system', $post)
            ->setValue('displayName', 'Добавить пост')
            ->setValue('displayName', 'Add post', 'en-US');
        $addPost->getProperty('locked')->setValue(true);
        $addPost->getProperty('componentName')->setValue('add');
        $addPost->getProperty('componentPath')->setValue('blog.post.add');

        $editPost = $structureCollection->add('edit', 'system', $post)
            ->setValue('displayName', 'Редактирование поста')
            ->setValue('displayName', 'Edit post', 'en-US');
        $editPost->getProperty('locked')->setValue(true);
        $editPost->getProperty('componentName')->setValue('edit');
        $editPost->getProperty('componentPath')->setValue('blog.post.edit');

        $viewPost = $structureCollection->add('view', 'system', $post)
            ->setValue('displayName', 'Просмотр поста')
            ->setValue('displayName', 'View post', 'en-US');
        $viewPost->getProperty('locked')->setValue(true);
        $viewPost->getProperty('componentName')->setValue('view');
        $viewPost->getProperty('componentPath')->setValue('blog.post.view');

        $draft = $structureCollection->add('drafts', 'system', $blogPage)
            ->setValue('displayName', 'Черновики блога')
            ->setValue('displayName', 'Drafts', 'en-US');
        $draft->getProperty('locked')->setValue(true);
        $draft->getProperty('componentName')->setValue('draft');
        $draft->getProperty('componentPath')->setValue('blog.draft');

        $draftView = $structureCollection->add('view', 'system', $draft)
            ->setValue('displayName', 'Просмотр черновика')
            ->setValue('displayName', 'View draft', 'en-US');
        $draftView->getProperty('locked')->setValue(true);
        $draftView->getProperty('componentName')->setValue('view');
        $draftView->getProperty('componentPath')->setValue('blog.draft.view');

        $draftEdit = $structureCollection->add('edit', 'system', $draft)
            ->setValue('displayName', 'Редактирование черновика')
            ->setValue('displayName', 'Edit draft', 'en-US');
        $draftEdit->getProperty('locked')->setValue(true);
        $draftEdit->getProperty('componentName')->setValue('edit');
        $draftEdit->getProperty('componentPath')->setValue('blog.draft.edit');

        $rejectedPost = $structureCollection->add('rejected', 'system', $blogPage)
            ->setValue('displayName', 'Отклонённые посты')
            ->setValue('displayName', 'Rejected posts', 'en-US');
        $rejectedPost->getProperty('locked')->setValue(true);
        $rejectedPost->getProperty('componentName')->setValue('reject');
        $rejectedPost->getProperty('componentPath')->setValue('blog.reject');

        $rejectedPostEdit = $structureCollection->add('edit', 'system', $rejectedPost)
            ->setValue('displayName', 'Редактировать отклонённый пост')
            ->setValue('displayName', 'Edit rejected posts', 'en-US');
        $rejectedPostEdit->getProperty('locked')->setValue(true);
        $rejectedPostEdit->getProperty('componentName')->setValue('edit');
        $rejectedPostEdit->getProperty('componentPath')->setValue('blog.reject.edit');

        $rejectedPostView = $structureCollection->add('view', 'system', $rejectedPost)
            ->setValue('displayName', 'Просмотреть отклонённый пост')
            ->setValue('displayName', 'View rejected posts', 'en-US');
        $rejectedPostView->getProperty('locked')->setValue(true);
        $rejectedPostView->getProperty('componentName')->setValue('view');
        $rejectedPostView->getProperty('componentPath')->setValue('blog.reject.view');

        $moderationPost = $structureCollection->add('needModeration', 'system', $blogPage)
            ->setValue('displayName', 'Посты на модерацию')
            ->setValue('displayName', 'Posts to moderate', 'en-US');
        $moderationPost->getProperty('componentName')->setValue('moderate');
        $moderationPost->getProperty('componentPath')->setValue('blog.moderate');

        $moderationPostEdit = $structureCollection->add('edit', 'system', $moderationPost)
            ->setValue('displayName', 'Редактировать посты на модерации')
            ->setValue('displayName', 'Edit posts to moderate', 'en-US');
        $moderationPostEdit->getProperty('componentName')->setValue('edit');
        $moderationPostEdit->getProperty('componentPath')->setValue('blog.moderate.edit');

        $moderationPostView = $structureCollection->add('own', 'system', $moderationPost)
            ->setValue('displayName', 'Просмотреть пост на модерации')
            ->setValue('displayName', 'View posts to moderate', 'en-US');
        $moderationPostView->getProperty('componentName')->setValue('own');
        $moderationPostView->getProperty('componentPath')->setValue('blog.moderate.own');

        $moderationPostAll = $structureCollection->add('all', 'system', $moderationPost)
            ->setValue('displayName', 'Очередь на модерацию')
            ->setValue('displayName', 'Turn on moderation', 'en-US');
        $moderationPostAll->getProperty('componentName')->setValue('all');
        $moderationPostAll->getProperty('componentPath')->setValue('blog.moderate.all');

        $comment = $structureCollection->add('blogcomment', 'system', $blogPage)
            ->setValue('displayName', 'Комментарий блога')
            ->setValue('displayName', 'Comment', 'en-US')
            ->setGUID('2099184c-013c-4653-8882-21c06d5e4e83');
        $comment->getProperty('locked')->setValue(true);
        $comment->getProperty('componentName')->setValue('comment');
        $comment->getProperty('componentPath')->setValue('blog.comment');

        $commentAdd = $structureCollection->add('add', 'system', $comment)
            ->setValue('displayName', 'Добавить комментарий')
            ->setValue('displayName', 'Add comment', 'en-US');
        $commentAdd->getProperty('locked')->setValue(true);
        $commentAdd->getProperty('componentName')->setValue('add');
        $commentAdd->getProperty('componentPath')->setValue('blog.comment.add');

        $author = $structureCollection->add('author', 'system', $blogPage)
            ->setValue('displayName', 'Авторы блога')
            ->setValue('displayName', 'Authors', 'en-US')
            ->setGUID('2ac90e34-16d0-4113-ab7c-de37c0287516');

        $author->getProperty('locked')->setValue(true);
        $author->getProperty('componentName')->setValue('author');
        $author->getProperty('componentPath')->setValue('blog.author');

        $profileAuthor = $structureCollection->add('profile', 'system', $author)
            ->setValue('displayName', 'Редактирование профиля автора')
            ->setValue('displayName', 'Edit author profile', 'en-US');
        $profileAuthor->getProperty('locked')->setValue(true);
        $profileAuthor->getProperty('componentName')->setValue('profile');
        $profileAuthor->getProperty('componentPath')->setValue('blog.author.profile');

        $viewAuthor = $structureCollection->add('view', 'system', $author)
            ->setValue('displayName', 'Просмотр профиля автора')
            ->setValue('displayName', 'View author profile', 'en-US');
        $viewAuthor->getProperty('locked')->setValue(true);
        $viewAuthor->getProperty('componentName')->setValue('view');
        $viewAuthor->getProperty('componentPath')->setValue('blog.author.view');

        $category = $categoryCollection->add('hunters')
            ->setValue('displayName', 'Охотницы')
            ->setValue('displayName', 'Hunters', 'en-US')
            ->setValue('metaTitle', 'Блог Охотниц за приведениями')
            ->setValue('h1', 'Блог Охотниц за приведениями')
            ->setValue('contents', '<p>Это блого обо всем на свете...</p>')
            ->setGUID('29449a5c-e0b0-42ad-9f1c-3d015540b024');

        $tag1 = $tagCollection->add()
            ->setValue('displayName', 'Призраки')
            ->setValue('displayName', 'Ghosts', 'en-US')
            ->setValue('slug','prizraki');

        $tag2 = $tagCollection->add()
            ->setValue('displayName', 'Привидения')
            ->setValue('displayName', 'Casts', 'en-US')
            ->setValue('slug','privideniya');

        $bives = $authorCollection->add()
            ->setValue('displayName', 'Бивес')
            ->setValue('displayName', 'Bives', 'en-US')
            ->setValue('h1', 'Бивес')
            ->setValue('contents', 'Бивес')
            ->setValue('contents', 'Bives', 'en-US')
            ->setValue('profile', $this->userSv)
            ->setValue('slug', 'bives');

        $buthead = $authorCollection->add()
            ->setValue('displayName', 'Батхед')
            ->setValue('displayName', 'Buthead', 'en-US')
            ->setValue('h1', 'Батхед')
            ->setValue('contents', 'Батхед')
            ->setValue('contents', 'Buthead', 'en-US')
            ->setValue('profile', $this->user)
            ->setValue('slug', 'buthead');


        $post1 = $postCollection->add()
            ->setValue('displayName', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Deviant behavior of ghosts and goblins and ways to influence him', 'en-US')
            ->setValue('metaTitle', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('h1', 'Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('announcement', '<p>Причины девиантного поведения домашних призраков кроются безусловно во влиянии MTV и пропаганде агрессивной альтернативной музыки.<br /><br />Также наблюдается рост домовых, практикующих экстремальное катание на роликовых коньках, скейт-бордах, BMX, что повышает общий уровень черепно-мозговых травм среди паранормальных существ. <br /><br />Не может не оказывать влияния проникновение культуры эмо в быт и уклад домашних призраков, что ведет к росту самоубийств и депрессивных состояний среди этих в общем-то жизнерадостных<br /> созданий.<br /><br />В качестве метода влияния на отклонения у домашний призраков я вижу их обращение в более позитивные и миролюбивые культуры, их пропаганда и популяризация в среде домашних призраков.<br /><br /><strong>Екатерина Джа-Дуплинская</strong></p>')
            ->setValue('announcement', '<p>Causes of deviant behavior of domestic ghosts certainly lie in the influence of MTV and the aggressive promotion of alternative music.</p>', 'en-US')
            ->setValue('contents', '<p>Причины девиантного поведения домашних призраков кроются безусловно во влиянии MTV и пропаганде агрессивной альтернативной музыки.<br /><br />Также наблюдается рост домовых, практикующих экстремальное катание на роликовых коньках, скейт-бордах, BMX, что повышает общий уровень черепно-мозговых травм среди паранормальных существ. <br /><br />Не может не оказывать влияния проникновение культуры эмо в быт и уклад домашних призраков, что ведет к росту самоубийств и депрессивных состояний среди этих в общем-то жизнерадостных<br /> созданий.<br /><br />В качестве метода влияния на отклонения у домашний призраков я вижу их обращение в более позитивные и миролюбивые культуры, их пропаганда и популяризация в среде домашних призраков.<br /><br /><strong>Екатерина Джа-Дуплинская</strong></p>')
            ->setValue('contents', '<p>Causes of deviant behavior home ghosts certainly lie in the influence of MTV and the aggressive promotion of alternative music . <br /> <br /> Also, an increase in brownies, practicing extreme inline skating , skateboarding , BMX, which increases the overall level of traumatic injuries of paranormal creatures. <br /> <br /> It can not affect the penetration of emo culture and way of life of the home of ghosts , which leads to an increase in suicide and depression among those in general cheerful <br /> creatures . <br /> <br / > as a method of influence on the deflection at home I see the ghosts of their treatment in a positive and peaceful culture , their propaganda and popularization in the home environment ghosts . <br /> <br /> <strong> Catherine Jar Duplinskaya </strong> </p>', 'en-US')
            ->setValue('author', $bives)
            ->setValue('slug', 'deviant')
            ->setGUID('8e675484-bea4-4fb5-9802-4750cc21e509')
            ->setValue('publishTime', new \DateTime('2010-08-11 17:35:00'));
        $post1->publish();

        $post2 = $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('displayName', 'Conflict resolution method UFO Renata Litvinova', 'en-US')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('announcement', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер.</p>')
            ->setValue('announcement', '<p>Renata Litvinova announced and allowed to use methods of conflict-author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen, try to remember how your evening ended yesterday.</p>', 'en-US')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('contents', '<p>Renata Litvinova announced and allowed to use methods of conflict- author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen , try to remember how your evening ended yesterday . Even if you can not remember , behave naturally, as if nothing had happened . Invite him to drink a cup of coffee, play chess , wash the dishes . <br /> <br /> 2) no need to be afraid . Even if the aliens landed you in the park or entrance , explain to them that a stranger UFOs do not communicate . They can offer you to get acquainted . decide - and suddenly it\'s fate ? <br /> <br /> 3)In all there are positive things . Even if after 10 years of marriage, you will find that your husband is an alien, do not rush to send into space negative questions. Space did everything right . But you still are not familiar with his mom.</p>', 'en-US')
            ->setValue('category', $category)
            ->setValue(BlogPost::FIELD_PUBLISH_STATUS, BlogPost::POST_STATUS_DRAFT)
            ->setValue('author', $bives)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty')
            ->setGUID('2ff677ee-765c-42ee-bb97-778f03f00c50')
            ->setValue('publishTime', new \DateTime('2010-08-14 17:35:00'));

        $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой-2')
            ->setValue('displayName', 'Conflict resolution method UFO Renata Litvinova', 'en-US')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('announcement', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер.</p>')
            ->setValue('announcement', '<p>Renata Litvinova announced and allowed to use methods of conflict-author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen, try to remember how your evening ended yesterday.</p>', 'en-US')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('category', $category)
            ->setValue(BlogPost::FIELD_PUBLISH_STATUS, BlogPost::POST_STATUS_REJECTED)
            ->setValue('author', $bives)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty-2')
            ->setValue('publishTime', new \DateTime('2010-08-14 17:35:00'));

        $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой-3')
            ->setValue('displayName', 'Conflict resolution method UFO Renata Litvinova', 'en-US')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('announcement', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер.</p>')
            ->setValue('announcement', '<p>Renata Litvinova announced and allowed to use methods of conflict-author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen, try to remember how your evening ended yesterday.</p>', 'en-US')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('category', $category)
            ->setValue(BlogPost::FIELD_PUBLISH_STATUS, BlogPost::POST_STATUS_NEED_MODERATE)
            ->setValue('author', $buthead)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty-3')
            ->setValue('publishTime', new \DateTime('2010-08-14 17:35:00'));

        $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой-4')
            ->setValue('displayName', 'Conflict resolution method UFO Renata Litvinova', 'en-US')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('announcement', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер.</p>')
            ->setValue('announcement', '<p>Renata Litvinova announced and allowed to use methods of conflict-author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen, try to remember how your evening ended yesterday.</p>', 'en-US')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('category', $category)
            ->setValue(BlogPost::FIELD_PUBLISH_STATUS, BlogPost::POST_STATUS_REJECTED)
            ->setValue('author', $buthead)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty-4')
            ->setValue('publishTime', new \DateTime('2010-08-14 17:35:00'));

        $post3 = $postCollection->add()
            ->setValue('displayName', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой-5')
            ->setValue('displayName', 'Conflict resolution method UFO Renata Litvinova', 'en-US')
            ->setValue('metaTitle', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('h1', 'Разрешение конфликтных ситуаций с НЛО методом Ренаты Литвиновой')
            ->setValue('announcement', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер.</p>')
            ->setValue('announcement', '<p>Renata Litvinova announced and allowed to use methods of conflict-author communication with UFOs. <br /> <br /> 1) Get yourself. If you met an alien in the morning in the kitchen, try to remember how your evening ended yesterday.</p>', 'en-US')
            ->setValue('contents', '<p>Рената Литвинова огласила и разрешила к применению авторские методы бесконфликтного общения с НЛО. <br /><br />1)&nbsp;&nbsp; &nbsp;Оставайтесь собой. Если встретили инопланетянина утром на кухне, постарайтесь вспомнить, как вчера закончился ваш вечер. Даже если вспомнить не можете, ведите себя естественно, как будто ничего и не было. Пригласите его выпить чашечку кофе, сыграть в шахматы, помыть посуду.<br /><br />2)&nbsp;&nbsp; &nbsp;Бояться не нужно. Даже если инопланетяне пристали к вам в парке или подъезде, объясните им, что с незнакомым НЛО не общаетесь. Они могут предложить вам познакомиться. Решайте &ndash; а вдруг это судьба?<br /><br />3)&nbsp;&nbsp; &nbsp; Во всем есть положительные моменты. Даже если спустя 10 лет совместной жизни, вы обнаружите, что ваш муж инопланетянин, не спешите посылать в космос негативные вопросы. Космос все сделал правильно. Зато вы до сих пор не знакомы с его мамой.</p>')
            ->setValue('category', $category)
            ->setValue('author', $buthead)
            ->setValue('slug', 'razreshenie_konfliktnyh_situacij_s_nlo_metodom_renaty-5')
            ->setValue('publishTime', new \DateTime('2010-08-14 17:35:00'));
        $post3->publish();


        $commentBranch = $commentCollection->add('branch1', 'branchComment')
            ->setValue('displayName', $post1->getValue('displayName'))
            ->setValue('post', $post1);

        /**
         * @var BlogCommentCollection $comment1
         */
        $comment1 = $commentCollection->add('comment1', 'comment',$commentBranch)
            ->setValue('displayName', 'Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Re: Deviant behavior of ghosts and goblins and ways to influence him', 'en-US')
            ->setValue('contents', '<p>О да. Недавно в нашем замке один милый маленький призрак покончил с собой. Мы были уверены, что это невозможно, но каким-то образом ему удалось раствориться в воде, наполняющей наш древний колодец.</p>')
            ->setValue('contents', '<p>Oh yeah. Recently in our castle one cute little ghost committed suicide. We were sure that it was impossible, but somehow he managed to dissolve in water, filling our ancient well.</p>', 'en-US')
            ->setValue('post', $post1)
            ->setValue('publishTime', new \DateTime('2012-11-15 15:07:31'))
            ->setValue('author',$bives);
        $comment1->publish();

        $comment2 = $commentCollection->add('comment2', 'comment', $comment1)
            ->setValue('displayName', 'Re: Re: Девиантное поведение призраков и домовых и способы влияния на него')
            ->setValue('displayName', 'Re: Re: Deviant behavior of ghosts and goblins and ways to influence him', 'en-US')
            ->setValue('contents', '<p>Возможно, вашего призрака еще удастся спасти. Попробуйте насыпать в колодец пару столовых ложек молотых семян бессмертника. Это должно помочь призраку снова сконденсировать свое нематериальное тело. И да, важно, чтобы семена были собраны в новолуние.</p>')
            ->setValue('contents', '<p>Perhaps your ghost still be salvaged. Try to pour into the well a couple of tablespoons of ground seeds Helichrysum. This should help the ghost again condense his intangible body. And yes, it is important that the seeds have been collected in the new moon.</p>', 'en-US')
            ->setValue('post', $post1)
            ->setValue('publishStatus', BlogComment::COMMENT_STATUS_REJECTED)
            ->setValue('publishTime', new \DateTime('2012-11-15 15:11:21'))
            ->setValue('author',$bives);

        $commentBranch2 = $commentCollection->add('branch2', 'branchComment')
            ->setValue('displayName', $post2->getValue('displayName'))
            ->setValue('post', $post2);

        $comment3 = $commentCollection->add('comment3', 'comment', $commentBranch2)
            ->setValue('displayName', 'важный вопрос')
            ->setValue('displayName', 'important question', 'en-US')
            ->setValue('contents', '<p>Существует ли разговорник для общения с НЛО? Основы этикета?</p>')
            ->setValue('contents', '<p>Is there a phrase book to communicate with UFO? Basics of etiquette?</p>', 'en-US')
            ->setValue('post', $post2)
            ->setValue('publishStatus', BlogComment::COMMENT_STATUS_PUBLISHED)
            ->setValue('publishTime', new \DateTime('2012-11-15 15:05:34'))
            ->setValue('author',$buthead);
        $comment3->publish();

        $commentCollection->add('comment1', 'comment', $comment2)
            ->setValue('displayName', 'Вложенный комментарий')
            ->setValue('displayName', 'nested comment', 'en-US')
            ->setValue('contents', '<p>О, да. Это вложенный комментарий.</p>')
            ->setValue('contents', '<p>Oh, yeah. This nested comment.</p>', 'en-US')
            ->setValue('post', $post1)
            ->setValue('publishStatus', BlogComment::COMMENT_STATUS_REJECTED)
            ->setValue('publishTime', new \DateTime('2012-11-15 15:07:31'))
            ->setValue('author',$buthead);

        $rssScenarioCollection->add()
            ->setValue('displayName', 'Scripting News')
            ->setValue('displayName', 'Scripting News', 'en-US')
            ->setValue('rssUrl', 'http://static.userland.com/gems/backend/rssTwoExample2.xml');

        $rssScenarioCollection->add()
            ->setValue('displayName', 'Хабрахабр / Захабренные / Тематические / Посты')
            ->setValue('displayName', 'Habrahabr / Zahabrennye / Thematic / Posts', 'en-US')
            ->setValue('rssUrl', 'http://habrahabr.ru/rss/hubs/');

        $rssScenarioCollection->add()
            ->setValue('displayName', 'DLE-News (windows-1251)')
            ->setValue('displayName', 'DLE-News (windows-1251)', 'en-US')
            ->setValue('rssUrl', 'http://dle-news.ru/rss.xml');
    }

    protected function installNews()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var SimpleCollection $newsCollection
         */
        $newsCollection = $this->getCollectionManager()->getCollection('newsItem');
        /**
         * @var NewsRssImportScenarioCollection $newsRssImportScenarioCollection
         */
        $rssScenarioCollection = $this->getCollectionManager()->getCollection('newsRssImportScenario');
        /**
         * @var SimpleHierarchicCollection $rubricCollection
         */
        $rubricCollection = $this->getCollectionManager()->getCollection('newsRubric');
        /**
         * @var SimpleCollection $subjectCollection
         */
        $subjectCollection = $this->getCollectionManager()->getCollection('newsSubject');

        $subject1 = $subjectCollection->add()
            ->setValue('displayName', 'Призраки')
            ->setValue('displayName', 'Ghosts', 'en-US')
            ->setValue('slug','prizraki');

        $subject2 = $subjectCollection->add()
            ->setValue('displayName', 'Привидения')
            ->setValue('displayName', 'Casts', 'en-US')
            ->setValue('slug','privideniya')
            ->setGUID('0d106acb-92a9-4145-a35a-86acd5c802c7');

        $newsPage = $structureCollection->add('novosti', 'system')
            ->setValue('displayName', 'Новости')
            ->setValue('displayName', 'News', 'en-US')
            ->setGUID('9ee6745f-f40d-46d8-8043-d959594628ce')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);

        $newsPage->getProperty('locked')->setValue(true);
        $newsPage->getProperty('componentName')->setValue('news');
        $newsPage->getProperty('componentPath')->setValue('news');

        $rubric = $structureCollection->add('rubriki', 'system', $newsPage)
            ->setValue('displayName', 'Новостная рубрика')
            ->setValue('displayName', 'Second rubrics', 'en-US')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462811');

        $rubric->getProperty('locked')->setValue(true);
        $rubric->getProperty('componentName')->setValue('rubric');
        $rubric->getProperty('componentPath')->setValue('news.rubric');

        $subject = $structureCollection->add('syuzhety', 'system', $newsPage)
            ->setValue('displayName', 'Новостной сюжет')
            ->setValue('displayName', 'News subject', 'en-US')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462822');

        $subject->getProperty('locked')->setValue(true);
        $subject->getProperty('componentName')->setValue('subject');
        $subject->getProperty('componentPath')->setValue('news.subject');

        $item = $structureCollection->add('item', 'system', $newsPage)
            ->setValue('displayName', 'Новость')
            ->setValue('displayName', 'News', 'en-US')
            ->setGUID('9ee6745f-f40d-46d8-8043-d95959462833');

        $item->getProperty('locked')->setValue(true);
        $item->getProperty('componentName')->setValue('item');
        $item->getProperty('componentPath')->setValue('news.item');

        $rubric = $rubricCollection->add('company')
            ->setValue('displayName', 'Новости сайта')
            ->setValue('displayName', 'Site news', 'en-US')
            ->setValue('metaTitle', 'Новости сайта')
            ->setValue('h1', 'Новости сайта')
            ->setGUID('8650706f-04ca-49b6-a93d-966a42377a61');

        $sport = $rubricCollection->add('sport')
            ->setValue('displayName', 'Новости спорта')
            ->setValue('displayName', 'Sport news', 'en-US')
            ->setValue('metaTitle', 'Новости спорта')
            ->setValue('h1', 'Новости спорта');

        $winterSports = $rubricCollection->add('winter', IObjectType::BASE, $sport)
            ->setValue('displayName', 'Зимний спорт')
            ->setValue('displayName', 'Winter sport', 'en-US')
            ->setValue('metaTitle', 'Зимний спорт')
            ->setValue('h1', 'Зимний спорт');

        $summerSports = $rubricCollection->add('summer', IObjectType::BASE, $sport)
            ->setValue('displayName', 'Летний спорт')
            ->setValue('displayName', 'Summer sport', 'en-US')
            ->setValue('metaTitle', 'Летний спорт')
            ->setValue('h1', 'Летний спорт');

        $snowboard = $rubricCollection->add('snowboard', IObjectType::BASE, $winterSports)
            ->setValue('displayName', 'Сноуборд')
            ->setValue('displayName', 'Snowboard', 'en-US')
            ->setValue('metaTitle', 'Сноуборд')
            ->setValue('h1', 'Сноуборд');

        $ski = $rubricCollection->add('ski', IObjectType::BASE, $winterSports)
            ->setValue('displayName', 'Лыжи')
            ->setValue('displayName', 'skiing', 'en-US')
            ->setValue('metaTitle', 'Лыжи')
            ->setValue('h1', 'Лыжи');

        $item = $newsCollection->add()
            ->setValue('displayName', 'Российские биатлонисты взяли первые три места')
            ->setValue('displayName', 'Russian biathletes took the first three places', 'en-US')
            ->setValue('metaTitle', 'Российские биатлонисты взяли первые три места')
            ->setValue('h1', 'Российские биатлонисты взяли первые три места')
            ->setValue('announcement', '<p>Чудо на олимпиаде в Сочи</p>')
            ->setValue('announcement', '<p>Miracle at the Olympics in Sochi</p>', 'en-US')
            ->setValue('contents', '<p>На олимпиаде в Сочи российские биатлонисты взяли все медали.</p>')
            ->setValue('contents', '<p>At the Olympic Games in Sochi Russian biathletes took all the medals.</p>', 'en-US')
            ->setValue('rubric', $ski)
            ->setValue('slug', 'biathlon');

        $volleyball = $rubricCollection->add('volleyball', IObjectType::BASE, $summerSports)
            ->setValue('displayName', 'Волейбол')
            ->setValue('displayName', 'Volleyball', 'en-US')
            ->setValue('metaTitle', 'Волейбол')
            ->setValue('h1', 'Волейбол');

        $item = $newsCollection->add()
            ->setValue('displayName', 'Названа причина социопатии современных зомби')
            ->setValue('displayName', 'Named reason sociopathy modern zombie', 'en-US')
            ->setValue('metaTitle', 'Названа причина социопатии современных зомби')
            ->setValue('h1', 'Названа причина социопатии современных зомби')
            ->setValue('announcement', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.</p>')
            ->setValue('announcement', "<p>The study lovers and haters of the Association of zombies, the main reason is sociopathy zombie food from McDonald's restaurants.</p>", 'en-US')
            ->setValue('contents', '<p>По результатам исследования Ассоциации любителей и ненавистников зомби, главной причиной социопатии зомби является еда из ресторанов МакДональдс.  Ученые давно бьют тревогу по поводу образа жизни молодых зомби и сейчас активно занялись пропагандой спорта, фитнес-клубов, активных игр на воздухе и популяризацией вегетарианской пищи среди представителей этого вида.  Пока ученые занимаются всеми этими вещами, молодые зомби курят по подъездам, впадают в депрессивные состоянии, примыкают к эмо-группировкам и совершенно не хотят работать.  &laquo;А между тем, этих ребят еще можно спасти, &mdash; комментирует Виктория Евдокимова, Охотница за привидениями со стажем, &mdash; и это в силах каждого из нас. Если увидите на улице одинокого зомби, подойдите и поинтересуйтесь, как обстоят дела с его девчонкой, какие у него планы на выходные, и что он делал прошлым летом&raquo;.</p>')
            ->setValue('contents', '<p>The study lovers and haters of the Association of zombies , the main reason is sociopathy zombie food from McDonald\'s restaurants . Scientists have long been sounding the alarm about the lifestyle of young zombies and is now actively engaged in the promotion of sports, fitness clubs , active games on the air and promotion of vegetarian food of this species . While scientists are engaged in all these things , young zombie smoking on the entrances , fall into the doldrums abut Emo groups and did not want to work. " In the meantime , these guys can still be saved - Victoria commented Evdokimov Ghost Hunter with experience - and it forces each of us. If you see on the street a lone zombie , go and ask how things are going with his girl , what are the plans for the weekend , and what he did last summer ». </p>', 'en-US')
            ->setValue('rubric', $rubric)
            ->setGUID('d6eb9ad1-667e-429d-a476-fa64c5eec115')
            ->setValue('slug', 'zombi')
            ->setValue('date', new \DateTime('2010-08-01 17:34:00'));

        $subjects = $item->getValue('subjects');
        $subjects->attach($subject1);
        $subjects->attach($subject2);

        $newsCollection->add()
            ->setValue('displayName', 'Смена состава в Отряде в бикини')
            ->setValue('displayName', 'Change in the composition in the Troop in bikini', 'en-US')
            ->setValue('metaTitle', 'Смена состава в Отряде в бикини')
            ->setValue('h1', 'Смена состава в Отряде в бикини')
            ->setValue('announcement', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.</p>')
            ->setValue('announcement', '<p>Note: The detachment occurred in bikini small permutations. In connection with a broken nail polish and a bad mood takes place Lolita Masha Andreeva Shikova.</p>', 'en-US')
            ->setValue('contents', '<p>Внимание: в составе Отряда в бикини произошли небольшие перестановки. В связи с испорченным маникюром и плохим настроением место Лолиты Андреевой займет Маша Шикова.  Маша Шикова имеет большой опыт в борьбе с домашними призраками и два столкновения с вампирами. Новая Охотница прекрасно вписалась в наш дружный женский коллектив и в ожидании интересных заданий уже пополнила свой гардероб пятью новыми комплектами бикини.   Лолита Андреева на редкость вяло комментирует свой выход из отряда. По нашим данным, это связано с тем, что маникюрный мастер девушки, с которым у нее был длительный роман, без предупреждения уехал в отпуск на Бали и оставил ее "подыхать в одиночестве".</p>')
            ->setValue('contents', '<p>Note: The detachment occurred in bikini small permutations . In connection with a broken nail polish and a bad mood takes place Lolita Masha Andreeva Shikova . Masha Shikova has extensive experience in dealing with household ghosts and two encounters with vampires. New Hunter perfectly fit into our friendly female staff in anticipation of interesting jobs already added your wardrobe with five new sets of bikini. Lolita Andreeva extremely sluggish comments on his way out of the squad . According to our data , this is due to the fact that girls manicure master , with whom she had a long affair , without warning, went on holiday to Bali and left her " to die alone ."</p>', 'en-US')
            ->setValue('rubric', $rubric)
            ->setGUID('35806ed8-1306-41b5-bbf9-fe2faedfc835')
            ->setValue('slug', 'bikini')
            ->setValue('date', new \DateTime('2010-08-03 17:36:00'));

        foreach (range(10, 50) as $num) {
            $newsCollection->add()
                ->setValue('displayName', 'Открыт метод устранения неврозов у привидений-' . $num)
                ->setValue('displayName', 'Open method of elimination of neuroses in ghosts-' . $num, 'en-US')
                ->setValue('metaTitle', 'Открыт метод устранения неврозов у привидений')
                ->setValue('h1', 'Открыт метод устранения неврозов у привидений-'.$num)
                ->setValue('announcement', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина<br />Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим<br />средством воздействия на привидения были, есть и будут красивые женские<br />ноги.</p>')
                ->setValue('announcement', '<p>By many scientific studies and laboratory experiments, Catherine <br /> Shvetsov, honored Hunter Ghost, made ​​the discovery that the best way to influence <br /> ghosts were, are and will <br /> beautiful female feet.</p>', 'en-US')
                ->setValue('contents', '<p>Путем долгих научных изысканий и лабораторных опытов, Екатерина Швецова, заслуженная Охотница за привидениями, сделала открытие, что лучшим средством воздействия на привидения были, есть и будут красивые женские ноги.  &laquo;Я долго шла к этому открытию, и на пути к нему совершила много других маленьких открытий, однако лучшее практическое применение получили именно мои ноги&raquo;, &mdash; рассказывает первооткрывательница.  В своем масштабном научном труде она дает рекомендации по правильному применению метода среди призраков и людей, а также эффективной длине юбке и оптимальной высоте каблука.</p>')
                ->setValue('contents', '<p>By many scientific studies and laboratory experiments , Catherine Shvetsov , honored Hunter Ghost , made ​​the discovery that the best way to influence the ghosts were, are and will be a beautiful female feet . " I have long sought this discovery, and the path to it has made a lot of other small openings , but the best practical application it got my feet " - discoverer says . In his large-scale scientific work she gives advice on the correct use of the method of ghosts and people , as well as the effective length of the skirt and the optimum height of the heel.</p>', 'en-US')
                ->setValue('rubric', $rubric)
                ->setValue('slug', 'privideniya-'.$num);
        }

        $rssScenarioCollection->add()
            ->setValue('displayName', 'Scripting News')
            ->setValue('displayName', 'Scripting News', 'en-US')
            ->setValue('rssUrl', 'http://static.userland.com/gems/backend/rssTwoExample2.xml');

        $rssScenarioCollection->add()
            ->setValue('displayName', 'Хабрахабр / Захабренные / Тематические / Посты')
            ->setValue('displayName', 'Habrahabr / Zahabrennye / Thematic / Posts', 'en-US')
            ->setValue('rssUrl', 'http://habrahabr.ru/rss/hubs/');

        $rssScenarioCollection->add()
            ->setValue('displayName', 'DLE-News (windows-1251)')
            ->setValue('displayName', 'DLE-News (windows-1251)', 'en-US')
            ->setValue('rssUrl', 'http://dle-news.ru/rss.xml');

    }

    protected function installGratitude() {

        /**
         * @var SimpleCollection $newsCollection
         */
        $newsCollection = $this->getCollectionManager()->getCollection('newsItem');
        /**
         * @var SimpleHierarchicCollection $rubricCollection
         */
        $rubricCollection = $this->getCollectionManager()->getCollection('newsRubric');

        $gratitude = $rubricCollection->add('gratitude')
            ->setValue('displayName', 'Благодарности')
            ->setValue('displayName', 'Thanks', 'en-US')
            ->setValue('metaTitle', 'Благодарности')
            ->setValue('h1', 'Благодарности')
            ->setGUID('4430239f-77f4-464d-b9eb-46f4c93eee8c');

        $newsCollection->add()
            ->setValue('displayName', 'Наташа')
            ->setValue('displayName', 'Natasha', 'en-US')
            ->setValue('metaTitle', 'Наташа Рублева, домохозяйка')
            ->setValue('h1', 'Наташа Рублева, домохозяйка')
            ->setValue('announcement', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло</p>')
            ->setValue('announcement', '<p>Not that I believe in ghosts, but a couple of months ago I started regularly find in our marital bed someones blonde hair, earrings and lipstick traces. Husband also denies the existence of ghosts, but so could not continue</p>', 'en-US')
            ->setValue('contents', '<p>Не то, чтобы я верю в привидения, но пару месяцев назад я начала регулярно находить в нашем супружеском ложе чьи-то светлые волосы, сережки и следы губной помады. Муж тоже отрицает существование привидений, однако так дальше продолжаться не могло. Я вызвала наряд охотниц за привидениями, и теперь мы избавлены от этих проблем. Сотрудница организации рекомендовала мне воспользоваться услугами спецподразделения &laquo;Отряд в бикини&raquo;. Я не пожалела, и, кажется, муж остался доволен.</p>')
            ->setValue('contents', '<p>Not that I believe in ghosts, but a couple of months ago I started regularly find in our marital bed someone\'s blonde hair, earrings and lipstick traces. Husband also denies the existence of ghosts, but so could not continue. I called the outfit hunters for ghosts, and now we are delivered from these problems. Employee organization was recommended to me to use the services of Special Forces "A squad in bikini." I do not regret, and it seems her husband was pleased.</p>', 'en-US')
            ->setValue('rubric', $gratitude)
            ->setValue('slug', 'natasha')
            ->setGUID('da5ec9a8-229c-4120-949c-2bb9eb641f24')
            ->setValue('date', new \DateTime('2013-06-24 19:11'));

        $newsCollection->add()
            ->setValue('displayName', 'Александр')
            ->setValue('displayName', 'Alexandr', 'en-US')
            ->setValue('metaTitle', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('h1', 'Александр, 35 лет, топ-менеджер сети строительных магазинов')
            ->setValue('announcement', '<p>С 18 лет меня довольно регулярно похищали инопланетяне.&nbsp;Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде</p>')
            ->setValue('announcement', '<p>With 18 years I pretty regularly abducted by aliens. But the last straw was the kidnapping in November of this year, during which they violently changed my eating habits</p>', 'en-US')
            ->setValue('contents', '<p>С 18 лет меня довольно регулярно похищали инопланетяне. Но последней каплей стало похищение в ноябре сего года, во время которого они насильственным способом изменили мои предпочтения в еде &ndash; я перестал смыслить свою жизнь без пива и чипсов. Я был вынужден обратиться к профессионалам. Как мне помогли Охотницы? Инициировав повторный сеанс связи, они совершили настоящий переворот. Теперь я замечательно обхожусь пряниками и шоколадом. Особую благодарность хочу выразить Охотнице Елене Жаровой за красивые глаза.</p>')
            ->setValue('contents', '<p>With 18 years I pretty regularly abducted by aliens. But the last straw was the kidnapping in November of this year, during which they violently changed my eating habits - I stopped smyslit life without beer and crisps. I was forced to turn to professionals. How can I help the Huntress? Initiating a second session, they made a real revolution. Now I get around wonderful cakes and chocolate. Special thanks to Helen Huntress Zharova for beautiful eyes.</p>', 'en-US')
            ->setValue('rubric', $gratitude)
            ->setGUID('60744128-996a-4cea-a937-c20ebc5c8c77')
            ->setValue('slug', 'aleksandr')
            ->setValue('date', new \DateTime('2013-06-24 19:14'));

    }

    protected function installStructure()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        /**
         * @var SimpleCollection $infoBlockCollection
         */
        $infoBlockCollection = $this->getCollectionManager()->getCollection('infoblock');
        /**
         * @var SimpleHierarchicCollection $menuCollection
         */
        $menuCollection = $this->getCollectionManager()->getCollection('menu');


        $parent = null;
        for ($i = 0; $i < 20; $i++) {
            $parent = $structureCollection->add('item' . $i, 'static', $parent)
                ->setValue('displayName', 'Элемент ' . $i)
                ->setValue('displayName', 'Item ' . $i, 'en-US');
        }

        /**
         * @var SimpleCollection $structureCollection
         */
        $layoutCollection = $this->getCollectionManager()->getCollection('layout');

        $layoutCollection->add()
            ->setValue('fileName', 'layout')
            ->setValue('displayName', 'Основной')
            ->setValue('displayName', 'Main', 'en-US')
            ->setGUID('d6cb8b38-7e2d-4b36-8d15-9fe8947d66c7');

        $this->blogLayout = $layoutCollection->add()
            ->setValue('fileName', 'blog')
            ->setValue('displayName', 'Блог')
            ->setValue('displayName', 'Blog', 'en-US');

        $structurePage = $structureCollection->add('structure', 'system')
            ->setValue('displayName', 'Структура')
            ->setValue('displayName', 'Structure', 'en-US');

        $structurePage->getProperty('locked')->setValue(true);
        $structurePage->getProperty('componentName')->setValue('structure');
        $structurePage->getProperty('componentPath')->setValue('structure');

        $menuPage = $structureCollection->add('menu', 'system', $structurePage)
            ->setValue('displayName', 'Меню')
            ->setValue('displayName', 'Menu', 'en-US');

        $menuPage->getProperty('locked')->setValue(true);
        $menuPage->getProperty('componentName')->setValue('menu');
        $menuPage->getProperty('componentPath')->setValue('structure.menu');

        $menuPage = $structureCollection->add('infoblock', 'system', $structurePage)
            ->setValue('displayName', 'Информационные блоки')
            ->setValue('displayName', 'Information block', 'en-US');

        $menuPage->getProperty('locked')->setValue(true);
        $menuPage->getProperty('componentName')->setValue('infoblock');
        $menuPage->getProperty('componentPath')->setValue('structure.infoblock');

        $structureInfoBlock = $infoBlockCollection->add('infoblock')
            ->setValue(InfoBlock::FIELD_INFOBLOCK_NAME, 'commonInfoBlock')
            ->setValue('displayName', 'Общие')
            ->setValue('displayName', 'Common', 'en-US');

        $structureInfoBlock
            ->setValue(InfoBlock::FIELD_PHONE_NUMBER, '<p>Телефон в Санкт-Петербурге: +7 (812) 309-03-15</p>')
            ->setValue(InfoBlock::FIELD_EMAIL, 'Общие вопросы: <a href="mailto:incoming@umi-cms.ru">incoming@umi-cms.ru</a>')
            ->setValue(InfoBlock::FIELD_EMAIL, 'Common question: <a href="mailto:incoming@umi-cms.ru">incoming@umi-cms.ru</a>', 'en-US')
            ->setValue(InfoBlock::FIELD_ADDRESS, 'БЦ «IT-Парк», Санкт-Петербург, ул. Красного Курсанта, д.25, лит.В')
            ->setValue(InfoBlock::FIELD_ADDRESS, 'BC «IT-Park», Sankt-Peterburg, ul. Krasnogo Kursanta, d.25, lit.B', 'en-US')
            ->setValue(InfoBlock::FIELD_LOGO, '<h1 class="blog-title">Demo lite twig</h1><p class="lead blog-description">Blank-шаблон созданный на Umicms 3</p>')
            ->setValue(InfoBlock::FIELD_LOGO, '<h1 class="blog-title">Demo lite twig</h1><p class="lead blog-description">Blank-template create on Umicms 3</p>', 'en-US')
            ->setValue(InfoBlock::FIELD_COPYRIGHT, '<p>Демо сайт разработан на <a href="http://getbootstrap.com">Bootstrap</a> компанией <a href="http://umi-cms.ru/">umi-cms.ru</a></p>')
            ->setValue(InfoBlock::FIELD_COPYRIGHT, '<p>Demo site build for <a href="http://getbootstrap.com">Bootstrap</a> by <a href="http://umi-cms.ru/">umi-cms.ru</a></p>', 'en-US')
            ->setValue(InfoBlock::FIELD_WIDGET_VK, '<div id="vk_groups" class="vk-groups" data-width="312" data-height="290" data-group-id="23325076" style="height: 290px; width: 312px; background-image: none; background-position: initial initial; background-repeat: initial initial;"><iframe name="fXD2eb29" frameborder="0" src="http://vk.com/widget_community.php?app=2402617&amp;width=312px&amp;_ver=1&amp;gid=23325076&amp;mode=NaN&amp;color1=&amp;color2=&amp;color3=&amp;height=290&amp;url=http%3A%2F%2Fwww.umi-cms.ru%2F&amp;145fa6498df" width="312" height="200" scrolling="no" id="vkwidget1" style="overflow: hidden; height: 290px;"></iframe></div>')
            ->setValue(InfoBlock::FIELD_WIDGET_FACEBOOK, '<div class="fb-like-box fb_iframe_widget" data-href="http://www.facebook.com/UMI.CMS" data-width="312" data-height="290" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false" fb-xfbml-state="rendered" fb-iframe-plugin-query="app_id=&amp;header=false&amp;height=290&amp;href=http%3A%2F%2Fwww.facebook.com%2FUMI.CMS&amp;locale=ru_RU&amp;sdk=joey&amp;show_border=false&amp;show_faces=true&amp;stream=false&amp;width=312"><span style="vertical-align: bottom; width: 312px; height: 290px;"><iframe name="f3df2c96ec" width="312px" height="290px" frameborder="0" allowtransparency="true" scrolling="no" title="fb:like_box Facebook Social Plugin" src="http://www.facebook.com/plugins/like_box.php?app_id=&amp;channel=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2FdgdTycPTSRj.js%3Fversion%3D41%23cb%3Df3acd61564%26domain%3Dwww.umi-cms.ru%26origin%3Dhttp%253A%252F%252Fwww.umi-cms.ru%252Ff891a948%26relation%3Dparent.parent&amp;header=false&amp;height=290&amp;href=http%3A%2F%2Fwww.facebook.com%2FUMI.CMS&amp;locale=ru_RU&amp;sdk=joey&amp;show_border=false&amp;show_faces=true&amp;stream=false&amp;width=312" class="" style="border: none; visibility: visible; width: 312px; height: 290px;"></iframe></span></div>')
            ->setValue(InfoBlock::FIELD_SHARE, '<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script> <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"></div>');

        /**
         * @var StaticPage $about
         */
        $about = $structureCollection->add('ob_otryade', 'static')
            ->setValue('displayName', 'Об отряде')
            ->setValue('displayName', 'About', 'en-US')
            ->setValue('metaTitle', 'Об отряде')
            ->setValue('h1', 'Об отряде')
            ->setValue('contents', '<p>Мы &mdash; отряд Охотниц за привидениями. Цвет волос, уровень IQ, размер груди, длина ног и количество высших образований не оказывают существенного влияния при отборе кадров в наши подразделения.</p><p>Единственно значимым критерием является наличие у Охотницы следующих навыков:</p><blockquote>метод десятипальцевой печати;<br /> тайский массаж;<br /> метод левой руки;<br /> техника скорочтения;</blockquote><p>Миссия нашей компании: Спасение людей от привидений во имя спокойствия самих привидений.<br /><br /> 12 лет нашей работы доказали, что предлагаемые нами услуги востребованы человечеством. За это время мы получили:</p><blockquote>1588 искренних благодарностей от клиентов; <br /> 260080 комплиментов; <br /> 5 интересных предложений руки и сердца.</blockquote><p>Нам не только удалось пережить кризис августа 1998 года, но и выйти на новый, рекордный уровень рентабельности.<br /> В своей работе мы используем             <strong>сверхсекретные</strong> супер-пупер-технологии.</p>')
            ->setValue('contents', '<p>We - Ghost Huntress squad . Hair color, level of IQ, breast size , leg length and the number of higher education does not have a significant influence in the selection of personnel in our units . </p> <p> only relevant criterion is the presence of the Huntress following skills : </p> <blockquote> Ten- printing method ; <br /> Thai massage ; <br /> method left hand ; <br /> skorochteniya appliances ; </blockquote> <p> our mission : Saving people from ghosts in the name of peace ghosts themselves . <br / > <br /> 12 years of our work proved that the services we offer in demand humanity. During this time we got : </p> <blockquote> 1588 sincere appreciation from the clients ; <br /> 260080 compliments ; <br /> 5 interesting marriage proposals . </blockquote> <p> We not only managed to survive the crisis in August 1998 , but also to achieve new record level of profitability . <br /> In our work we use <strong> top-secret </strong> super -duper technology.</p>', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_CURRENT_SHOWN)
            ->setGUID('d534fd83-0f12-4a0d-9853-583b9181a948');

        $about->getProperty('componentName')->setValue('structure');
        $about->getProperty('componentPath')->setValue('structure');

        $no = $structureCollection->add('no', 'static', $about)
            ->setValue('displayName', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('displayName', 'Work for which we never take', 'en-US')
            ->setValue('metaTitle', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('h1', 'Работа, за которую мы никогда не возьмемся')
            ->setValue('contents', '<ul><li>Безосновательный вызов призраков на дом</li><li>Гадания на картах, кофейной гуще, блюдечке</li><li>Толкование снов</li><li>Интим-услуги. Мы не такие!</li></ul>')
            ->setValue('contents', '<ul><li> groundless call ghosts home </li> <li> tarot cards, tea leaves, a silver platter </li> <li> Interpretation of Dreams </li> <li> Intimacy services. We are not! </li></ul>', 'en-US')
            ->setGUID('3d765c94-bb80-4e8f-b6d9-b66c3ea7a5a4');
        $no->getProperty('componentName')->setValue('structure');
        $no->getProperty('componentPath')->setValue('structure');


        $service = $structureCollection->add('services', 'static')
            ->setValue('displayName', 'Услуги')
            ->setValue('displayName', 'Services', 'en-US')
            ->setValue('metaTitle', 'Услуги')
            ->setValue('h1', 'Услуги')
            ->setValue('contents', '<p><strong>Дипломатические переговоры с домовыми</strong></p><p>Домовые требуют особого подхода. Выгонять домового из дома категорически запрещено, т.к. его призвание &mdash; охранять дом. Однако, некоторые домовые приносят своим хозяевам немало хлопот из-за своенравного характера. <br /><br />Хорошие отношения с домовым &mdash; наша работы. Правильно провести дипломатические переговоры с домовым, с учетом его знака зодиака, типа температмента и других психографических характеристик, настроить его на позитивный лад, избавить от личных переживаний, разобраться в ваших разногласиях и провести результативные переговоры может грамотный специалист с широким набором характеристик и знаний.<br /><br /><em>Работает Охотница Ольга Карпова <br />Спецнавыки: паранормальная дипломатия, психология поведения духов и разрешение конфликтов</em></p><p><br /><br /><strong>Изгнание призраков царских кровей и других элитных духов<br /></strong><br />Вы купили замок? Хотите провести профилактические работы? Или уже столкнулись с присутствием призраков один на один?<br /><br />Вам &mdash; в наше элитное подразделение. Духи царских кровей отличаются кичливым поведением и высокомерием, однако до сих пор подразделение Охотниц в бикини всегда справлялось с поставленными задачами.<br /><br />Среди наших побед:</p><p>- тень отца Гамлета, вызвавшая переполох в женской раздевалке фитнес-клуба; <br />- призрак Ленина, пытающийся заказать роллы Калифорния на вынос; <br />- призрак Цезаря на неделе миланской моды в Москве.&nbsp; <br /><br /><em>Работает Охотница Елена&nbsp; Жарова <br />Спецнавыки: искусство душевного разговора</em></p>')
            ->setValue('contents', '<p><strong> Diplomatic negotiations with household </strong> </p> <p> homes require a special approach. Brownie expel from home is strictly prohibited , as his vocation - guard the house . However, some brownies bring their owners a lot of trouble because of the capricious nature . <br /> <br /> Good relations with brownies - our work. Right to hold diplomatic talks with brownies , given its zodiac sign , type temperatmenta and other psychographic characteristics that set it in a positive way , get rid of personal experiences , sort out your differences and conduct productive negotiations can a qualified specialist with a wide range of characteristics and knowledge. < br /> <br /> <em> Works Hunter Olga Karpova <br /> Craft : paranormal diplomacy , psychology behavior spirits and conflict resolution </em> </p> <p> <br /> <br /> <strong> Exile ghosts of royal blood and other elite spirits <br /> </strong> <br /> you bought the castle? Want to conduct preventive work ? Or have already faced with the presence of ghosts alone ? <br /> <br /> You - in our elite unit . Perfume royal blood differ snobby behavior and arrogance , but still in a bikini division Huntress always cope with the task . <br /> <br /> Our Ratio : </p> <p> - shadow of Hamlet\'s father , caused a stir in women\'s locker room fitness club ; <br /> - the ghost of Lenin, trying to order take-out California rolls ; <br /> - the ghost of Caesar on the Milan fashion week in Moscow. <br /> <br /> <em> Works Hunter Elena Zharov <br /> Craft : The Art of Mental Talk </em></p>', 'en-US')
            ->setGUID('98751ebf-7f76-4edb-8210-c2c3305bd8a0');
        $service->getProperty('componentName')->setValue('structure');
        $service->getProperty('componentPath')->setValue('structure');

        $price = $structureCollection->add('price', 'static')
            ->setValue('displayName', 'Тарифы и цены')
            ->setValue('displayName', 'Tariffs and prices', 'en-US')
            ->setValue('metaTitle', 'Тарифы и цены')
            ->setValue('h1', 'Тарифы и цены')
            ->setValue('contents', '<p><strong>Если вас регулярно посещают привидения, призраки, НЛО, &laquo;Летучий голландец&raquo;, феномен черных рук, демоны, фантомы, вампиры и чупакабры...</strong></p><p>Мы предлагаем вам воспользоваться нашим <strong>тарифом абонентской платы</strong>, который составляет <span style="color: #ff6600;"><strong>1 995</strong></span> у.е. в год. Счастливый год без привидений!</p><p><strong>Если паранормальное явление появился в вашей жизни неожиданно, знакомьтесь с прайсом*:<br /></strong></p><blockquote>Дипломатические переговоры с домовым &ndash; <span style="color: #ff6600;"><strong>120</strong></span> у.е.<br />Нейтрализация вампира &ndash; <span style="color: #ff6600;"><strong>300</strong></span> у.е.<br />Изгнание привидения стандартного &ndash; <span style="color: #ff6600;"><strong>200</strong></span> у.е.<br />Изгнание привидений царей, принцев и принцесс, вождей революций и другой элиты &ndash; <span style="color: #ff6600;"><strong>1250</strong></span> у.е.<br />Борьба с НЛО &ndash; рассчитывается <span style="text-decoration: underline;">индивидуально</span>.</blockquote><p><strong>Специальная услуга: </strong>ВЫЗОВ ОТРЯДА В БИКИНИ</p><p><span style="font-size: x-small;"><em>Стандартные услуги в сочетании с эстетическим удовольствием!</em></span></p><p><strong>Скидки оптовым и постоянным клиентам:</strong><br />При заказе устранения от 5 духов (любого происхождения, включая элиту) предоставляется скидка 12% от общей цены. Скидки по акциям не суммируются.</p><p><span>*Цена за одну особь!</span></p>')
            ->setValue('contents', '<p><strong> If you regularly attend a ghost , ghosts , UFOs, "Flying Dutchman" , the phenomenon of black hands , demons, ghosts , vampires and chupacabra ... </strong> </p> <p> We offer you use our <strong> rate monthly fee </strong>, which is <span style="color: #ff6600;"> <strong> 1995 </strong> </span> cu per year. Happy year without ghosts ! </p> <p> <strong> If paranormal phenomenon appeared in your life unexpectedly , meet and Price *: <br /> </strong> </p> <blockquote> Diplomatic negotiations with brownies - <span style="color: #ff6600;"> <strong> 120 </strong> </span> <br /> cu Neutralization vampire - <span style="color: #ff6600;"> <strong> 300 </strong> </span> <br /> cu Exile ghosts standard - <span style="color: #ff6600;"> <strong> 200 </strong> </span> <br /> cu Exile ghosts of kings, princes and princesses , and other leaders of the revolution of the elite - <span style="color: #ff6600;"> <strong> 1250 </strong> </span> <br /> cu Fighting UFO - calculated individually <span style="text-decoration: underline;"> </span>. </blockquote> <p> <strong> Special service : </strong> CALL SQUAD in bikini </p> <p> <span style = "font-size: x-small;"> <em> Standard services combined with aesthetic pleasure ! </em> </span> </p> <p> <strong> Discount wholesale and regular customers : </strong> <br /> When ordering removal from 5 spirits ( of any origin , including the elite ) and 12% discount off the total price . Discounts on shares are not cumulative . </p> <p> <span> * Price for one individual ! </span></p>', 'en-US')
            ->setGUID('c81d6d87-25c6-4ab8-b213-ef3a0f044ce6');
        $price->getProperty('componentName')->setValue('structure');
        $price->getProperty('componentPath')->setValue('structure');


        $menuItem1 = $structureCollection->add('menu_item_1', 'static')
            ->setValue('displayName', 'Элемент меню 1')
            ->setValue('displayName', 'Menu Item 1', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem1->getProperty('componentName')->setValue('structure');
        $menuItem1->getProperty('componentPath')->setValue('structure');

        $menuItem11 = $structureCollection->add('menu_item_1_1', 'static', $menuItem1)
            ->setValue('displayName', 'Элемент меню 1.1')
            ->setValue('displayName', 'Menu Item 1.1', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem11->getProperty('componentName')->setValue('structure');
        $menuItem11->getProperty('componentPath')->setValue('structure');

        $menuItem12 = $structureCollection->add('menu_item_1_2', 'static', $menuItem1)
            ->setValue('displayName', 'Menu Item 1.2')
            ->setValue('displayName', 'Menu Item 1.2', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem12->getProperty('componentName')->setValue('structure');
        $menuItem12->getProperty('componentPath')->setValue('structure');

        $menuItem121 = $structureCollection->add('menu_item_1_2_1', 'static', $menuItem12)
            ->setValue('displayName', 'Menu Item 1.2.1')
            ->setValue('displayName', 'Menu Item 1.2.1', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem121->getProperty('componentName')->setValue('structure');
        $menuItem121->getProperty('componentPath')->setValue('structure');

        $menuItem122 = $structureCollection->add('menu_item_1_2_2', 'static', $menuItem12)
            ->setValue('displayName', 'Menu Item 1.2.2')
            ->setValue('displayName', 'Menu Item 1.2.2', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem122->getProperty('componentName')->setValue('structure');
        $menuItem122->getProperty('componentPath')->setValue('structure');

        $menuItem1221 = $structureCollection->add('menu_item_1_2_2_1', 'static', $menuItem122)
            ->setValue('displayName', 'Menu Item 1.2.2.1')
            ->setValue('displayName', 'Menu Item 1.2.2.1', 'en-US')
            ->setValue('inMenu', true)
            ->setValue('submenuState', StructureElement::SUBMENU_ALWAYS_SHOWN);
        $menuItem1221->getProperty('componentName')->setValue('structure');
        $menuItem1221->getProperty('componentPath')->setValue('structure');


        $bottomMenu = $menuCollection->add('bottomMenu', Menu::TYPE)
            ->setValue(Menu::FIELD_DISPLAY_NAME, 'Нижнее меню')
            ->setValue(Menu::FIELD_NAME, 'bottomMenu');

        $menuCollection->add('bottomMenu', MenuInternalItem::TYPE, $bottomMenu)
            ->setValue(MenuInternalItem::FIELD_DISPLAY_NAME, 'Главная')
            ->setValue(MenuInternalItem::FIELD_PAGE_RELATION, $about);

        $menuCollection->add('bottomMenu-1', MenuInternalItem::TYPE, $bottomMenu)
            ->setValue(MenuInternalItem::FIELD_DISPLAY_NAME, 'Работа, за которую мы никогда не возьмемся')
            ->setValue(MenuInternalItem::FIELD_PAGE_RELATION, $no);

        $menuCollection->add('bottomMenu-2', MenuInternalItem::TYPE, $bottomMenu)
            ->setValue(MenuInternalItem::FIELD_DISPLAY_NAME, 'Услуги и цены')
            ->setValue(MenuInternalItem::FIELD_PAGE_RELATION, $service);

        $menuCollection->add('bottomMenu-3', MenuExternalItem::TYPE, $bottomMenu)
            ->setValue(MenuExternalItem::FIELD_DISPLAY_NAME, 'Внешняя ссылка')
            ->setValue(MenuExternalItem::FIELD_RESOURCE_URL, 'http://ya.ru/');

    }

    protected function dropTables()
    {
        $connection = $this->dbCluster->getConnection();

        $tables = $connection->getDriver()->getSchemaManager($connection)->listTableNames();
        foreach ($tables as $table) {
            $connection->getDriver()->getSchemaManager($connection)->dropTable($table);
        }
    }

    private function installSearch()
    {
        /**
         * @var SimpleHierarchicCollection $structureCollection
         */
        $structureCollection = $this->getCollectionManager()->getCollection('structure');
        $searchRoot = $structureCollection->add('search', 'system')
            ->setValue('displayName', 'Поиск')
            ->setGUID('9ee6745f-f40d-46d8-8043-d901234628ce');

        $searchRoot->getProperty('locked')->setValue(true);
        $searchRoot->getProperty('componentName')->setValue('search');
        $searchRoot->getProperty('componentPath')->setValue('search');


//        $this->searchIndexApi->buildIndex('structure');
        $this->searchIndexApi->buildIndex('newsRubric');
        $this->searchIndexApi->buildIndex('newsItem');
        $this->searchIndexApi->buildIndex('newsSubject');
        $this->searchIndexApi->buildIndex('blogCategory');
        $this->searchIndexApi->buildIndex('blogPost');
        $this->searchIndexApi->buildIndex('blogComment');
        $this->commit();
    }

    /**
     * Записывает изменения всех объектов в БД (бизнес транзакция),
     * запуская перед этим валидацию объектов.
     * Если при сохранении какого-либо объекта возникли ошибки - все изменения
     * автоматически откатываются
     * @throws InvalidObjectsException если объекты не прошли валидацию
     * @throws RuntimeException если транзакция не успешна
     * @return self
     */
    protected function commit()
    {
        $currentUser = $this->usersModule->user()->get('68347a1d-c6ea-49c0-9ec3-b7406e42b01e');

        $persister = $this->getObjectPersister();

        /**
         * @var ICmsObject|IRecoverableObject $object
         */
        foreach ($persister->getModifiedObjects() as $object) {
            $collection = $object->getCollection();
            if ($collection instanceof IRecoverableCollection && $object instanceof IRecoverableObject) {
                $collection->createBackup($object);
            }
        }
        foreach ($persister->getNewObjects() as $object) {
            $object->owner = $currentUser;
            $object->setCreatedTime();
        }
        foreach ($persister->getModifiedObjects() as $object) {
            $object->editor = $currentUser;
            $object->setUpdatedTime();
        }

        $invalidObjects = $persister->getInvalidObjects();

        if (count($invalidObjects)) {
            foreach ($invalidObjects as $object)
            {
                var_dump([$object->getTypePath() . '#' . $object->guid => $object->getValidationErrors()]);
            }

            throw new InvalidObjectsException(
                $this->translate('Cannot persist objects. Objects are not valid.'),
                $invalidObjects
            );
        }

        $this->getObjectPersister()->commit();

    }
}
