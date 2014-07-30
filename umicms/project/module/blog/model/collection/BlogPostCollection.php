<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\collection;

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IObject;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\CmsPageCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\model\object\BlogAuthor;
use umicms\project\module\blog\model\object\BlogPost;
use umicms\project\module\blog\model\object\PostStatus;

/**
 * Коллекция постов блога.
 *
 * @method CmsSelector|BlogPost[] select() Возвращает селектор для выбора постов.
 * @method BlogPost get($guid, $localization = ILocalesService::LOCALE_CURRENT) Возвращает пост по GUID
 * @method BlogPost getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает пост по id
 * @method BlogPost add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает пост
 */
class BlogPostCollection extends CmsPageCollection
{
    const HANDLER_DRAFT = 'draft';
    const HANDLER_MODERATE_OWN = 'moderateOwn';
    const HANDLER_MODERATE_ALL = 'moderateAll';
    const HANDLER_REJECT = 'reject';
    /**
     * Возвращает пост по его источнику.
     * @param string $source
     * @throws NonexistentEntityException если пост с указанным источником не существует
     * @return BlogPost
     */
    public function getPostBySource($source)
    {
        $selector = $this->select()
            ->where(BlogPost::FIELD_SOURCE)
            ->equals($source);

        $post = $selector->getResult()->fetch();

        if (!$post instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find blog post by source "{source}".',
                    ['source' => $source]
                )
            );
        }

        return $post;
    }

    /**
     * Возвращает пост по URI, с учетом статуса публикации.
     * @param string $uri URI
     * @param string $localization указание на локаль, в которой загружается объект.
     * По умолчанию объект загружается в текущей локали. Можно указать другую конкретную локаль
     * @throws NonexistentEntityException если не удалось получить объект
     * @return BlogPost
     */
    public function getByUri($uri, $localization = ILocalesService::LOCALE_CURRENT)
    {
        $selector = $this->select()
            ->localization($localization)
            ->where(BlogPost::FIELD_PAGE_SLUG)->equals($uri)
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
                ->equals(PostStatus::GUID_PUBLISHED);

        $page = $selector->getResult()->fetch();

        if (!$page instanceof BlogPost) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $uri, 'collection' => $this->getName()]
            ));
        }

        return $page;
    }

    /**
     * Возвращает селектор черновиков.
     * @return CmsSelector|BlogPost
     */
    public function getDrafts()
    {
        return $this->select()
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
                ->equals(PostStatus::GUID_DRAFT);
    }

    /**
     * Возвращает черновик по GUID.
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить черновик
     * @return BlogPost
     */
    public function getDraft($guid)
    {
        $selector = $this->getDrafts()
            ->where(BlogPost::FIELD_GUID)->equals($guid);

        $draft = $selector->getResult()->fetch();

        if (!$draft instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find draft by guid "{guid}".',
                    ['guid' => $guid]
                )
            );
        }

        return $draft;
    }

    /**
     * Возвращает черновик по идентификатору.
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить черновик
     * @return BlogPost
     */
    public function getDraftById($id)
    {
        $selector = $this->getDrafts()
            ->where(BlogPost::FIELD_IDENTIFY)->equals($id);

        $draft = $selector->getResult()->fetch();

        if (!$draft instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find draft by id "{id}".',
                    ['id' => $id]
                )
            );
        }

        return $draft;
    }

    /**
     * Возвращает черновик по URI.
     * @param string $uri URI
     * @param string $localization указание на локаль, в которой загружается объект.
     * По умолчанию объект загружается в текущей локали. Можно указать другую конкретную локаль
     * @throws NonexistentEntityException если не удалось получить объект
     * @return BlogPost
     */
    public function getDraftByUri($uri, $localization = ILocalesService::LOCALE_CURRENT)
    {
        $selector = $this->getDrafts()
            ->localization($localization)
            ->where(BlogPost::FIELD_PAGE_SLUG)
            ->equals($uri);

        $blogDraft = $selector->getResult()->fetch();

        if (!$blogDraft instanceof BlogPost) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $uri, 'collection' => $this->getName()]
            ));
        }

        return $blogDraft;
    }

    /**
     * Возвращает список постов, требующих модерацию.
     * @return CmsSelector|BlogPost
     */
    public function getNeedModeratePosts()
    {
        return $this->select()
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
                ->equals(PostStatus::GUID_NEED_MODERATION);
    }

    /**
     * Возвращает пост, требующий модерации по GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалось получить пост требующий модерации
     * @return null|BlogPost
     */
    public function getNeedModeratePost($guid)
    {
        $selector = $this->getNeedModeratePosts()
            ->where(BlogPost::FIELD_GUID)->equals($guid);

        $moderatePost = $selector->getResult()->fetch();

        if (!$moderatePost instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find post for moderation with guid "{guid}".',
                    ['guid' => $guid]
                )
            );
        }

        return $moderatePost;
    }

    /**
     * Возвращает пост, требующий модерации по Id
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить пост требующий модерации
     * @return null|BlogPost
     */
    public function getNeedModeratePostById($id)
    {
        $selector = $this->getNeedModeratePosts()
            ->where(BlogPost::FIELD_IDENTIFY)->equals($id);

        $moderatePost = $selector->getResult()->fetch();

        if (!$moderatePost instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find post for moderation with id "{id}".',
                    ['id' => $id]
                )
            );
        }

        return $moderatePost;
    }

    /**
     * Возвращает пост, требующий модерации по URI.
     * @param string $uri URI
     * @param string $localization указание на локаль, в которой загружается объект.
     * По умолчанию объект загружается в текущей локали. Можно указать другую конкретную локаль
     * @throws NonexistentEntityException если не удалось получить объект
     * @return BlogPost
     */
    public function getNeedModeratePostByUri($uri, $localization = ILocalesService::LOCALE_CURRENT)
    {
        $selector = $this->getNeedModeratePosts()
            ->localization($localization)
            ->where(BlogPost::FIELD_PAGE_SLUG)
            ->equals($uri);

        $moderatePost = $selector->getResult()->fetch();

        if (!$moderatePost instanceof BlogPost) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $uri, 'collection' => $this->getName()]
            ));
        }

        return $moderatePost;
    }

    /**
     * Возвращает список отклоненных постов.
     * @return CmsSelector|BlogPost
     */
    public function getRejectedPosts()
    {
        return $this->select()
            ->where(BlogPost::FIELD_STATUS . '.' . PostStatus::FIELD_GUID)
            ->equals(PostStatus::GUID_REJECTED);
    }

    /**
     * Возвращает отклоненный пост по GUID
     * @param string $guid
     * @throws NonexistentEntityException если не удалить получить отклоненный пост
     * @return null|BlogPost
     */
    public function getRejectedPost($guid)
    {
        $selector = $this->getRejectedPosts()
            ->where(BlogPost::FIELD_GUID)->equals($guid);

        $rejectedPost = $selector->getResult()->fetch();

        if (!$rejectedPost instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find rejected post by guid "{guid}".',
                    ['guid' => $guid]
                )
            );
        }

        return $rejectedPost;
    }

    /**
     * Возвращает отклоненный пост по Id
     * @param int $id
     * @throws NonexistentEntityException если не удалось получить отклоненный пост
     * @return null|BlogPost
     */
    public function getRejectedPostById($id)
    {
        $selector = $this->getRejectedPosts()
            ->where(BlogPost::FIELD_IDENTIFY)->equals($id);

        $rejectedPost = $selector->getResult()->fetch();

        if (!$rejectedPost instanceof BlogPost) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot find rejected post by id "{id}".',
                    ['id' => $id]
                )
            );
        }

        return $rejectedPost;
    }

    /**
     * Возвращает отклоненный пост по URI
     * @param string $uri URI
     * @param string $localization указание на локаль, в которой загружается объект.
     * По умолчанию объект загружается в текущей локали. Можно указать другую конкретную локаль
     * @throws NonexistentEntityException если не удалось получить объект
     * @return null|BlogPost
     */
    public function getRejectedPostByUri($uri, $localization = ILocalesService::LOCALE_CURRENT)
    {
        $selector = $this->getRejectedPosts()
            ->localization($localization)
            ->where(BlogPost::FIELD_PAGE_SLUG)
            ->equals($uri);

        $rejectedPost = $selector->getResult()->fetch();

        if (!$rejectedPost instanceof BlogPost) {
            throw new NonexistentEntityException($this->translate(
                'Cannot get page by slug "{slug}" from collection "{collection}".',
                ['slug' => $uri, 'collection' => $this->getName()]
            ));
        }

        return $rejectedPost;
    }

    /**
     * {@inheritdoc}
     */
    public function activate(IActiveAccessibleObject $object)
    {
        if ($object instanceof BlogPost && $object->author instanceof BlogAuthor) {
            $object->author->recalculatePostsCount();
        }

        return parent::activate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(IActiveAccessibleObject $object)
    {
        if ($object instanceof BlogPost && $object->author instanceof BlogAuthor) {
            $object->author->recalculatePostsCount();
        }

        return parent::deactivate($object);
    }

    /**
     * {@inheritdoc}
     */
    public function trash(IRecyclableObject $object)
    {
        if ($object instanceof BlogPost && $object->author instanceof BlogAuthor) {
            $object->author->recalculatePostsCount();
        }

        return parent::trash($object);
    }

    /**
     * {@inheritdoc}
     */
    public function untrash(IRecyclableObject $object)
    {
        if ($object instanceof BlogPost && $object->author instanceof BlogAuthor) {
            $object->author->recalculatePostsCount();
        }

        return parent::untrash($object);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(IObject $object)
    {
        if ($object instanceof BlogPost && $object->author instanceof BlogAuthor) {
            $object->author->recalculatePostsCount();
        }

        return parent::delete($object);
    }
}
