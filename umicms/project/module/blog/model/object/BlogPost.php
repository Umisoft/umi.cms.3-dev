<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\model\object;

use umi\orm\collection\ICollection;
use umi\orm\metadata\IObjectType;
use umi\orm\object\property\IPropertyFactory;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\users\model\UsersModule;

/**
 * Пост блога от зарегистрированного пользователя.
 *
 * @property BlogAuthor $author автор поста
 */
class BlogPost extends BaseBlogPost
{
    /**
     * Тип поста блога
     */
    const TYPE = 'blogPost';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
    /**
     * Форма отправки поста на модерацию
     */
    const FORM_MODERATE_POST = 'moderate';
    /**
     * Форма помещения поста в черновики
     */
    const FORM_DRAFT_POST = 'draft';

    /**
     * @var UsersModule $usersModule модуль "Пользователи"
     */
    private $usersModule;

    /**
     * Конструктор.
     * @param ICollection $collection
     * @param IObjectType $objectType
     * @param IPropertyFactory $propertyFactory
     * @param UsersModule $usersModule
     */
    public function __construct(ICollection $collection, IObjectType $objectType, IPropertyFactory $propertyFactory, UsersModule $usersModule)
    {
        parent::__construct($collection, $objectType, $propertyFactory);

        $this->usersModule = $usersModule;
    }

    /**
     * Возвращает URL поста.
     * @param bool $isAbsolute абсолютный ли URL
     * @throws RuntimeException если невозможно определить сайтовый компонент-обработчик
     * @return string
     */
    public function getPageUrl($isAbsolute = false)
    {
        if (!$this->status instanceof PostStatus) {
            throw new RuntimeException(
                $this->translate(
                    'Cannot detect handler for blog post with guid "{guid}". Status is unknown.',
                    ['guid' => $this->guid]
                )
            );
        }

        switch ($this->status->guid) {
            case PostStatus::GUID_DRAFT : {
                $handler = BlogPostCollection::HANDLER_DRAFT;
                break;
            }
            case PostStatus::GUID_REJECTED: {
                $handler = BlogPostCollection::HANDLER_REJECT;
                break;
            }
            case PostStatus::GUID_NEED_MODERATION : {
                if ($this->author instanceof BlogAuthor && $this->usersModule->getCurrentUser() === $this->author->profile) {
                    $handler = BlogPostCollection::HANDLER_MODERATE_OWN;
                } else {
                    $handler = BlogPostCollection::HANDLER_MODERATE_ALL;
                }
                break;
            }
            default : {
                $handler = ICmsCollection::HANDLER_SITE;
            }
        }

        return $this->getUrlManager()->getSitePageUrl($this, $isAbsolute, $handler);
    }

    /**
     * Изменяет статус публикации.
     * @param string|null $value статус публикации
     * @return $this
     */
    public function setStatus($value)
    {
        $publishStatusProperty = $this->getProperty(self::FIELD_STATUS);
        $publishStatusProperty->setValue($value);

        if ($publishStatusProperty->getIsModified()) {
            if ($this->author instanceof BlogAuthor) {
                $this->author->recalculatePostsCount();
            }
        }

        return $this;
    }

    /**
     * Изменяет автора комментария.
     * @param BlogAuthor|null $value автор
     * @return $this
     */
    public function setAuthor($value)
    {
        if ($this->author instanceof BlogAuthor) {
            $this->author->recalculateCommentsCount();
        }

        $this->getProperty(self::FIELD_AUTHOR)->setValue($value);

        if ($value instanceof BlogAuthor) {
            $value->recalculatePostsCount();
        }

        return $this;
    }
}
