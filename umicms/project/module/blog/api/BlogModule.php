<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api;

use umi\orm\selector\condition\IFieldConditionGroup;
use umi\rss\IRssFeed;
use umi\rss\IRssFeedAware;
use umi\rss\RssItem;
use umi\rss\TRssFeedAware;
use umicms\exception\InvalidArgumentException;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\hmvc\url\IUrlManagerAware;
use umicms\hmvc\url\TUrlManagerAware;
use umicms\module\BaseModule;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\collection\BlogAuthorCollection;
use umicms\project\module\blog\api\collection\BlogCategoryCollection;
use umicms\project\module\blog\api\collection\BlogCommentCollection;
use umicms\project\module\blog\api\collection\BlogPostCollection;
use umicms\project\module\blog\api\collection\BlogRssImportScenarioCollection;
use umicms\project\module\blog\api\collection\BlogTagCollection;
use umicms\project\module\blog\api\object\BlogAuthor;
use umicms\project\module\blog\api\object\BlogCategory;
use umicms\project\module\blog\api\object\BlogComment;
use umicms\project\module\blog\api\object\BlogPost;
use umicms\project\module\blog\api\object\BlogRssImportScenario;
use umicms\project\module\blog\api\object\BlogTag;
use umicms\project\module\users\api\UsersModule;

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
     * @var UsersModule $usersModule API пользователей
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
     * Возвращает селектор для выборки постов.
     * @return CmsSelector|BlogPost[]
     */
    public function getPosts()
    {
        return $this->post()->select()
            ->where(BlogPost::FIELD_PUBLISH_STATUS)->equals(BlogPost::POST_STATUS_PUBLISH)
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
     * @param int $limit максимальное количество авторов
     * @return CmsSelector|BlogTag[]
     */
    public function getAuthors($limit = null)
    {
        $authors = $this->author()->select();

        if ($limit) {
            $authors->limit($limit);
        }

        return $authors;
    }

    /**
     * Возвращает селектор для выбора постов автора.
     * @param BlogAuthor[] $authors категория
     * @param int $limit
     * @return CmsSelector|BlogPost[]
     */
    public function getPostsByAuthor(array $authors = [], $limit = null)
    {
        $posts = $this->getPosts($limit);

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
     * @param int $limit
     * @return CmsSelector|BlogPost[]
     */
    public function getPostByCategory(array $categories = [], $limit = null)
    {
        $posts = $this->getPosts($limit);

        $posts->begin(IFieldConditionGroup::MODE_OR);
        foreach ($categories as $category) {
            $posts->where(BlogPost::FIELD_CATEGORY)->equals($category);
        }
        $posts->end();

        return $posts;
    }

    /**
     * Возвращает селектор для выборки комментариев.
     * @param int $limit
     * @return CmsSelector|BlogComment[]
     */
    public function getComment($limit = null)
    {
        $comments = $this->comment()->select()
            ->orderBy(BlogComment::FIELD_PUBLISH_TIME, CmsSelector::ORDER_DESC);

        if ($limit) {
            $comments->limit($limit);
        }

        return $comments;
    }

    /**
     * Возвращает селектор для выборки комментариев к посту.
     * @param BlogPost $blogPost
     * @param int $limit
     * @return CmsSelector|BlogComment[]
     */
    public function getCommentByPost(BlogPost $blogPost, $limit = null)
    {
        $comments = $this->getComment($limit)
            ->where(BlogComment::FIELD_POST)->equals($blogPost);

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

        foreach ($rssFeed->getRssItems() as $item) {
            $this->importRssPost($item, $blogRssImportScenario);
        }

        return $this;
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
                $blogPost->publishTime->setTimestamp($item->getDate()->getTimestamp());
                $blogPost->publishTime->setTimezone($item->getDate()->getTimezone());
            }
            if ($item->getUrl()) {
                $blogPost->source = $item->getUrl();
            }
            $blogPost->slug = $blogPost->guid;
            $blogPost->category = $blogRssImportScenario->category;

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
     * Возвращает текущего автора блога.
     * @throws InvalidArgumentException
     * @return mixed
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

        if (isset($this->currentAuthor) && !$this->currentAuthor instanceof BlogAuthor) {
            throw new InvalidArgumentException(
                $this->translate(
                    'Method parameter "{param} should be instance of "{class}".',
                    [
                        'param' => 'currentAuthor',
                        'class' => 'BlogAuthor'
                    ]
                )
            );
        }

        return $this->currentAuthor;
    }

    /**
     * Возвращает список черновиков текущего пользователя.
     * @return CmsSelector|BlogPost
     */
    public function getOwnDrafts()
    {
        return $this->post()->getDrafts()
            ->where(BlogPost::FIELD_AUTHOR)->equals($this->getCurrentAuthor());
    }
}
