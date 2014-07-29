<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model;

use umi\orm\metadata\IObjectType;
use umi\orm\selector\condition\IFieldConditionGroup;
use umi\rss\IRssFeed;
use umi\rss\IRssFeedAware;
use umi\rss\RssItem;
use umi\rss\TRssFeedAware;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\module\BaseModule;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\collection\BlogAuthorCollection;
use umicms\project\module\blog\model\collection\BlogCategoryCollection;
use umicms\project\module\blog\model\collection\BlogCommentCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\blog\model\collection\BlogRssImportScenarioCollection;
use umicms\project\module\blog\model\collection\BlogTagCollection;
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\blog\model\object\BlogBranchComment;
use umicms\project\module\blog\model\object\BlogCategory;
use umicms\project\module\blog\model\object\BlogComment;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\module\blog\model\object\BlogRssImportScenario;
use umicms\project\module\blog\model\object\BlogTag;
use umicms\project\module\blog\model\object\CommentStatus;
use umicms\project\module\blog\model\object\PostStatus;
use umicms\project\module\users\model\object\BaseUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Публичное API модуля "Блоги".
 */
class BlogModule extends BaseModule implements IRssFeedAware, IUrlManagerAware
{
    use TRssFeedAware;
    use TUrlManagerAware;

    /**
     * @var BlogAuthor $currentAuthor текущий автор блога
     */
    protected $currentAuthor;

    /**
     * @var int $maxPostsCount максимальное количество постов у тега
     */
    protected $maxPostsCount;
    /**
     * @var int $minPostsCount минимальное количество постов у тега
     */
    protected $minPostsCount;

    /**
     * @var UsersModule $usersModule модуль "Пользователи"
     */
    protected $usersModule;

    public function __construct(UsersModule $usersModule)
    {
        $this->usersModule = $usersModule;
    }

    /**
     * Возвращает коллекцию постов.
     * @return BlogPostCollection
     */
    public function post()
    {
        return $this->getCollection('blogPost');
    }

    /**
     * Возвращает коллекцию авторов.
     * @return BlogAuthorCollection
     */
    public function author()
    {
        return $this->getCollection('blogAuthor');
    }

    /**
     * Возвращает коллекцию категорий.
     * @return BlogCategoryCollection
     */
    public function category()
    {
        return $this->getCollection('blogCategory');
    }

    /**
     * Возвращает коллекцию тэгов.
     * @return BlogTagCollection
     */
    public function tag()
    {
        return $this->getCollection('blogTag');
    }

    /**
     * Возвращает коллекцию комментариев.
     * @return BlogCommentCollection
     */
    public function comment()
    {
        return $this->getCollection('blogComment');
    }

    /**
     * Возвращает коллекцию импортируемых RSS-лент.
     * @return BlogRssImportScenarioCollection
     */
    public function rssImport()
    {
        return $this->getCollection('blogRssImportScenario');
    }

    /**
     * Возвращает коллекцию статусов постов.
     * @return CmsCollection
     */
    public function postStatus()
    {
        return $this->getCollection('blogPostStatus');
    }

    /**
     * Возвращает коллекцию статусов комментариев.
     * @return CmsCollection
     */
    public function commentStatus()
    {
        return $this->getCollection('blogCommentStatus');
    }

    /**
     * Создает пост от имени текущего автора.
     * @param string $typeName имя дочернего типа
     * @return BlogPost
     */
    public function addPost($typeName = IObjectType::BASE)
    {
        $post = $this->post()->add($typeName);
        $post->author = $this->getCurrentAuthor();

        return $post;
    }

    /**
     * Возвращает селектор для выборки постов.
     * @return CmsSelector|BlogPost[]
     */
    public function getPosts()
    {
        return $this->post()->select()
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
                ->equals(PostStatus::GUID_PUBLISHED)
            ->orderBy(BlogPost::FIELD_PUBLISH_TIME, CmsSelector::ORDER_DESC);
    }

    /**
     * Строит RSS-ленту.
     * @param string $title заголовок RSS-ленты
     * @param string $description описание RSS-ленты
     * @param CmsSelector|BlogPost[] $postSelector список постов
     * @return IRssFeed
     */
    public function getPostRssFeed($title, $description, CmsSelector $postSelector)
    {
        $rssFeed = $this->createRssFeed(
            $this->getUrlManager()->getProjectUrl(true),
            $title,
            $description
        );

        foreach ($postSelector as $blogPost) {
            $rssFeed->addItem()
                ->setTitle($blogPost->h1)
                ->setContent($blogPost->announcement)
                ->setUrl($blogPost->getPageUrl(true))
                ->setDate($blogPost->publishTime);
        }

        return $rssFeed;
    }

    /**
     * Возвращает селектор для выборки постов указанных категорий.
     * @param BlogCategory[] $categories список GUID категорий блога
     * @return CmsSelector|BlogCategory[]
     */
    public function getCategoryPost(array $categories = [])
    {
        $posts = $this->getPosts();

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($categories as $category) {
            $posts->where(BlogPost::FIELD_CATEGORY)->equals($category);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выборки постов указанных тэгов.
     * @param BlogTag[] $tags список GUID тэгов постов
     * @return CmsSelector|BlogPost[]
     */
    public function getTagPost(array $tags = [])
    {
        $posts = $this->getPosts();

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($tags as $tag) {
            $posts->where(BlogPost::FIELD_TAGS)->equals($tag);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выборки постов в указанной категории.
     * @param BlogCategory|null $parentCategory GUID категории
     * @return CmsSelector|BlogPost[]
     */
    public function getCategories(BlogCategory $parentCategory = null)
    {
        return $this->category()->selectChildren($parentCategory);
    }

    /**
     * Возвращает селектор для выборки тэгов.
     * @return CmsSelector|BlogTag[]
     */
    public function getTags()
    {
        return $this->tag()->select();
    }

    /**
     * Возвращает селектор для выборки авторов.
     * @return CmsSelector|BlogTag[]
     */
    public function getAuthors()
    {
        $authors = $this->author()->select();

        return $authors;
    }

    /**
     * Возвращает селектор для выбора постов автора.
     * @param BlogAuthor[] $authors категория
     * @return CmsSelector|BlogPost[]
     */
    public function getPostsByAuthor(array $authors = [])
    {
        $posts = $this->getPosts();

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($authors as $author) {
            $posts->where(BlogPost::FIELD_AUTHOR)->equals($author);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выбора постов категорий.
     * @param BlogCategory[] $categories категории блога
     * @return CmsSelector|BlogPost[]
     */
    public function getPostByCategory(array $categories = [])
    {
        $posts = $this->getPosts();

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($categories as $category) {
            $posts->where(BlogPost::FIELD_CATEGORY)->equals($category);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Создает комментарий от имени текущего автора.
     * @param string $typeName имя дочернего типа
     * @param BlogPost $post пост, к которому добавляется комментарий
     * @param null|BlogComment $parentComment родительский комментарий
     * @return BlogComment
     */
    public function addComment($typeName = BlogComment::TYPE, BlogPost $post, BlogComment $parentComment = null)
    {
        if (is_null($parentComment)) {
            $parentComment = $this->getBranchComment($post);
        }

        $comment = $this->comment()->add(null, $typeName, $parentComment);
        $comment->post = $post;
        $comment->slug = $comment->getGUID();

        if ($this->hasCurrentAuthor()) {
            $comment->author = $this->getCurrentAuthor();
        }

        return $comment;
    }

    /**
     * Возвращает селектор для выборки комментариев.
     * @return CmsSelector|BlogComment[]
     */
    public function getComments()
    {
        $comments = $this->comment()->select()
            ->orderBy(BlogComment::FIELD_HIERARCHY_LEVEL, CmsSelector::ORDER_ASC)
            ->orderBy(BlogComment::FIELD_PUBLISH_TIME, CmsSelector::ORDER_DESC);

        return $comments;
    }

    /**
     * Возвращает селектор для выборки опубликованных комментариев к посту.
     * @param BlogPost $blogPost
     * @return CmsSelector|BlogComment[]
     */
    public function getCommentsByPost(BlogPost $blogPost)
    {
        $comments = $this->getComments()
            ->types([BlogComment::TYPE . '*'])
            ->where(BlogComment::FIELD_POST)->equals($blogPost)
            ->where(BlogComment::FIELD_STATUS . '.' . CommentStatus::FIELD_GUID)->in(
                [
                    CommentStatus::GUID_PUBLISHED,
                    CommentStatus::GUID_UNPUBLISHED
                ]
            );

        return $comments;
    }

    /**
     * Возвращает селектор для выборки опубликованных и требующих модерации комментариев к посту.
     * @param BlogPost $blogPost
     * @return CmsSelector|BlogComment[]
     */
    public function getCommentByPostWithNeedModeration(BlogPost $blogPost)
    {
        $comments = $this->getComments()
            ->types([BlogComment::TYPE . '*'])
            ->where(BlogComment::FIELD_POST)->equals($blogPost)
            ->where(BlogComment::FIELD_STATUS . '.' . CommentStatus::FIELD_GUID)->in(
                [
                    CommentStatus::GUID_PUBLISHED,
                    CommentStatus::GUID_NEED_MODERATION,
                    CommentStatus::GUID_UNPUBLISHED
                ]
            );

        return $comments;
    }

    /**
     * Возвращает селектор для выбора постов по тэгу.
     * @param BlogTag[] $tags
     * @return CmsSelector|BlogPost[]
     */
    public function getPostByTag(array $tags = [])
    {
        $posts = $this->getPosts();

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($tags as $tag) {
            $posts->where(BlogPost::FIELD_TAGS)->equals($tag);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Выполнение импорта постов из RSS-ленты.
     * @param BlogRssImportScenario $blogRssImportScenario импортируемая RSS-лента
     * @throws RuntimeException когда не удалось загрузить RSS-ленту
     * @return $this
     */
    public function importRss(BlogRssImportScenario $blogRssImportScenario)
    {
        try {
            $xml = \GuzzleHttp\get($blogRssImportScenario->rssUrl)
                ->xml(['object' => false]);
        } catch (\Exception $e) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot load RSS feed from url {url}.',
                    ['url' => $blogRssImportScenario->rssUrl]
                ),
                0,
                $e
            );
        }

        $rssFeed = $this->createRssFeedFromSimpleXml($xml);

        $items = $rssFeed->getRssItems();

        foreach ($items as $item) {
            $this->importRssPost($item, $blogRssImportScenario);
        }

        return count($items);
    }

    /**
     * Возвращает облако тегов.
     * @param int $minFontSize минимальный размер шрифта
     * @param int $maxFontSize максимальный размер шрифта
     * @return array
     */
    public function getTagCloud($minFontSize, $maxFontSize)
    {
        $tagsCloud = [];

        /** @var BlogTag[] $tags */
        $tags = $this->getTags()->getResult()->fetchAll();
        shuffle($tags);

        foreach ($tags as $tag) {
            $tagsCloud[] = [
                'tag' => $tag,
                'weight' => $this->getTagWeight($tag, $minFontSize, $maxFontSize)
            ];
        }

        return $tagsCloud;
    }

    /**
     * Возвращает текущего автора блога.
     * Если автора не существует - создает нового.
     * @throws RuntimeException в случае, если текущий автор не установлен
     * @return BlogAuthor
     */
    public function getCurrentAuthor()
    {
        if ($this->currentAuthor) {
            return $this->currentAuthor;
        }

        $this->currentAuthor = $this->author()->select()
            ->where(BlogAuthor::FIELD_PROFILE)->equals($this->usersModule->getCurrentUser())
            ->getResult()
            ->fetch();

        if ($this->usersModule->isAuthenticated()) {
            $this->currentAuthor = $this->createAuthor(
                $this->usersModule->getCurrentUser()
            );
        }

        if (!$this->currentAuthor instanceof BlogAuthor) {
            throw new RuntimeException(
                $this->translate(
                    'Current author should be instance of "{class}".',
                    [
                        'class' => BlogAuthor::className()
                    ]
                )
            );
        }

        return $this->currentAuthor;
    }

    /**
     * Проверяет существование текущего автора блога.
     * @return bool
     */
    public function hasCurrentAuthor()
    {
        if (!$this->currentAuthor && $this->usersModule->isAuthenticated()) {
            $this->currentAuthor = $this->author()->select()
                ->where(BlogAuthor::FIELD_PROFILE)->equals($this->usersModule->getCurrentUser())
                ->getResult()
                ->fetch();
        }

        return $this->currentAuthor instanceof BlogAuthor;
    }

    /**
     * Создает автора на основе юзера.
     * @param BaseUser $user
     * @return BlogAuthor
     */
    public function createAuthor(BaseUser $user)
    {
        if ($this->hasCurrentAuthor()) {
            return $this->getCurrentAuthor();
        }

        return $this->author()->add(IObjectType::BASE)
            ->setValue(BlogAuthor::FIELD_PAGE_SLUG, $user->login)
            ->setValue(BlogAuthor::FIELD_DISPLAY_NAME, $user->displayName)
            ->setValue(BlogAuthor::FIELD_PROFILE, $user);
    }

    /**
     * Возвращает список черновиков текущего пользователя.
     * @return CmsSelector|BlogPost
     */
    public function getOwnDrafts()
    {
        if ($this->hasCurrentAuthor()) {
            return $this->post()->getDrafts()
                ->where(BlogPost::FIELD_AUTHOR)->equals($this->getCurrentAuthor());
        } else {
            return $this->post()->emptySelect();
        }
    }

    /**
     * Возвращает список постов текущего пользователя, требующих модерации.
     * @return CmsSelector|BlogPost
     */
    public function getOwnModerate()
    {
        if ($this->hasCurrentAuthor()) {
            return $this->post()->getNeedModeratePosts()
                ->where(BlogPost::FIELD_AUTHOR)->equals($this->getCurrentAuthor());
        } else {
            return $this->post()->emptySelect();
        }
    }

    /**
     * Возвращает список отклоненных постов текущего пользователя.
     * @return CmsSelector|BlogPost
     */
    public function getOwnRejected()
    {
        if ($this->hasCurrentAuthor()) {
            return $this->post()->getRejectedPosts()
                ->where(BlogPost::FIELD_AUTHOR)->equals($this->getCurrentAuthor());
        } else {
            return $this->post()->emptySelect();
        }
    }

    /**
     * Импортирует пост из RSS-ленты.
     * @param RssItem $item
     * @param BlogRssImportScenario $blogRssImportScenario
     */
    protected function importRssPost(RssItem $item, BlogRssImportScenario $blogRssImportScenario)
    {
        try {
            $this->post()->getPostBySource($item->getUrl());
        } catch (NonexistentEntityException $e) {
            $blogPost = $this->post()->add();
            if ($item->getTitle()) {
                $blogPost->displayName = $item->getTitle();
                $blogPost->h1 = $item->getTitle();
            }
            if ($item->getContent()) {
                $blogPost->announcement = $item->getContent();
            }
            if ($item->getDate()) {
                $blogPost->publishTime = new \DateTime();
                $blogPost->publishTime->setTimestamp($item->getDate()->getTimestamp());
            }
            if ($item->getUrl()) {
                $blogPost->source = $item->getUrl();
            }
            $blogPost->slug = $blogPost->guid;
            $blogPost->category = $blogRssImportScenario->category;
            $blogPost->author = $blogRssImportScenario->author;
            $blogPost->status = $blogRssImportScenario->postStatus;

            foreach ($blogRssImportScenario->tags as $subject) {
                $blogPost->tags->attach($subject);
            }
        }
    }

    /**
     * Возвращает вес тэга.
     * @param BlogTag $tag
     * @param int $minFontSize минимальный размер шрифта
     * @param int $maxFontSize максимальный размер шрифта
     * @return float
     */
    protected function getTagWeight(BlogTag $tag, $minFontSize, $maxFontSize)
    {
        $postsCount = $tag->postsCount;

        $minPostCount = $this->getMinPostsCount();
        $maxPostCount = $this->getMaxPostsCount();

        if ($minPostCount - $maxPostCount != 0) {
            $weight =
                ($postsCount - $this->getMinPostsCount()) / ($this->getMaxPostsCount() - $this->getMinPostsCount())
                *
                ($maxFontSize - $minFontSize) + $minFontSize;
        } else {
            $weight = $minFontSize;
        }

        return $weight;
    }

    /**
     * Возвращает минимальное количество постов у тега.
     * @return int
     */
    protected function getMinPostsCount()
    {
        if (!$this->minPostsCount) {
            /** @var BlogTag $tag */
            $tag = $this->getTags()
                ->fields(['postsCount'])
                ->orderBy('postsCount', CmsSelector::ORDER_ASC)
                ->limit(1)
                ->getResult()
                ->fetch();

            $this->minPostsCount = $tag->postsCount;
        }

        return $this->minPostsCount;
    }

    /**
     * Возвращает максимальное количество постов у тега.
     * @return int
     */
    protected function getMaxPostsCount()
    {
        if (!$this->maxPostsCount) {
            /** @var BlogTag $tag */
            $tag = $this->getTags()
                ->fields([BlogTag::FIELD_POSTS_COUNT])
                ->orderBy(BlogTag::FIELD_POSTS_COUNT, CmsSelector::ORDER_DESC)
                ->limit(1)
                ->getResult()
                ->fetch();

            $this->maxPostsCount = $tag->postsCount;
        }

        return $this->maxPostsCount;
    }

    /**
     * Возвращает ветку комментариев к посту.
     * @param BlogPost $blogPost
     * @return CmsSelector|BlogComment[]
     */
    protected function getBranchCommentByPost(BlogPost $blogPost)
    {
        $branchComments = $this->getComments()
            ->types([BlogBranchComment::TYPE])
            ->where(BlogComment::FIELD_POST)->equals($blogPost)
            ->limit(1)
            ->result()
            ->fetch();

        return $branchComments;
    }

    /**
     * Возвращает корень ветки комментариев к посту.
     * @param BlogPost $post
     * @return BlogComment
     */
    protected function getBranchComment(BlogPost $post)
    {
        $branchComment = $this->getBranchCommentByPost($post);

        if ($branchComment instanceof BlogBranchComment) {
            return $branchComment;
        }

        $comment = $this->comment()->add(null, BlogBranchComment::TYPE);
        $comment->displayName = $post->displayName;
        $comment->post = $post;
        $comment->slug = $comment->getGUID();

        return $comment;
    }
}
