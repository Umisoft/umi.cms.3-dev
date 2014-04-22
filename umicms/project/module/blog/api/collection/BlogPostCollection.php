<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\PageCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\blog\api\object\BlogPost;

/**
 * Коллекция постов блога.
 *
 * @method CmsSelector|BlogPost[] select() Возвращает селектор для выбора постов.
 * @method BlogPost get($guid, $withLocalization = false) Возвращает пост по GUID
 * @method BlogPost getById($objectId, $withLocalization = false) Возвращает пост по id
 * @method BlogPost add($typeName = IObjectType::BASE) Создает и возвращает пост
 * @method BlogPost getByUri($uri, $withLocalization = false) Возвращает пост по его последней части ЧПУ
 */
class BlogPostCollection extends PageCollection
{
    const HANDLER_DRAFT = 'draft';
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
     * Возвращает селектор черновиков.
     * @return CmsSelector|BlogPost
     */
    public function getDrafts()
    {
        return $this->selectInternal()
            ->where(BlogPost::FIELD_TRASHED)->equals(false)
            ->where(BlogPost::FIELD_ACTIVE)->equals(false);
    }

    /**
     * Возвращает черновик по GUID.
     * @param string $guid
     * @return BlogPost
     */
    public function getDraft($guid)
    {
        $draft = $this->getDrafts()
            ->where(BlogPost::FIELD_GUID)->equals($guid);

        return $draft->getResult()->fetch();
    }

    /**
     * Возвращает черновик по идентификатору.
     * @param int $id
     * @return BlogPost
     */
    public function getDraftById($id)
    {
        $draft = $this->getDrafts()
            ->where(BlogPost::FIELD_IDENTIFY)->equals($id);

        return $draft->getResult()->fetch();
    }

    /**
     * Возвращает черновик по URI.
     * @param string $uri URI
     * @param bool $withLocalization загружать ли значения локализованных свойств объекта.
     * @throws NonexistentEntityException если не удалось получить объект
     * @return BlogPost
     */
    public function getDraftByUri($uri, $withLocalization = false)
    {
        $selector = $this->getDrafts()
            ->withLocalization($withLocalization)
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
}
