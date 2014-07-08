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

use DateTime;
use umi\hmvc\acl\ComponentRoleProvider;
use umi\orm\object\property\calculable\ICounterProperty;
use umi\orm\collection\ICollection;
use umi\orm\metadata\IObjectType;
use umi\orm\object\property\IPropertyFactory;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\blog\model\collection\BlogPostCollection;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Пост блога.
 *
 * @property string $contentsRaw необработанный контент поста
 * @property BlogAuthor $author автор поста
 * @property string $announcement анонс
 * @property BlogCategory|null $category категория поста
 * @property IManyToManyObjectSet $tags тэги, к которым относится пост
 * @property DateTime $publishTime дата публикации поста
 * @property string $publishStatus статус публикации поста
 * @property int $commentsCount количество комментариев к посту
 * @property string $oldUrl старый URL поста
 * @property string $source источник поста
 */
class BlogPost extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения необработанного контента поста
     */
    const FIELD_PAGE_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
    /**
     * Имя поля для хранения анонса поста
     */
    const FIELD_ANNOUNCEMENT = 'announcement';
    /**
     * Имя поля для хранения названия категории, к которой относится пост
     */
    const FIELD_CATEGORY = 'category';
    /**
     * Имя поля для хранения тэгов, относящихся к посту
     */
    const FIELD_TAGS = 'tags';
    /**
     * Имя поля для хранения даты и времени публикации поста
     */
    const FIELD_PUBLISH_TIME = 'publishTime';
    /**
     * Имя поля для хранения статуса публикации поста
     */
    const FIELD_PUBLISH_STATUS = 'publishStatus';
    /**
     * Имя поля для хранения количества комментариев к посту
     */
    const FIELD_COMMENTS_COUNT = 'commentsCount';
    /**
     * Имя поля для хранения источника поста
     */
    const FIELD_SOURCE = 'source';
    /**
     * Форма добавления поста
     */
    const FORM_ADD_POST = 'addPost';
    /**
     * Форма редактирования поста
     */
    const FORM_EDIT_POST = 'editPost';
    /**
     * Форма публикации поста
     */
    const FORM_PUBLISH_POST = 'publish';
    /**
     * Форма отправки поста на модерацию
     */
    const FORM_MODERATE_POST = 'moderate';
    /**
     * Форма отклонения поста для публикации
     */
    const FORM_REJECT_POST = 'reject';
    /**
     * Форма помещения поста в черновики
     */
    const FORM_DRAFT_POST = 'draft';
    /**
     * Статус поста: черновик
     */
    const POST_STATUS_DRAFT = 'draft';
    /**
     * Статус поста: опубликован
     */
    const POST_STATUS_PUBLISHED = 'published';
    /**
     * Статус поста: отклонён
     */
    const POST_STATUS_REJECTED = 'rejected';
    /**
     * Статус поста: требует модерации
     */
    const POST_STATUS_NEED_MODERATE = 'moderate';

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
     * Мутатор для контентного поля.
     * @param string $contents контент поста
     * @param string $locale локаль
     * @return $this
     */
    public function setContents($contents, $locale)
    {
        $this->getProperty(self::FIELD_PAGE_CONTENTS, $locale)
            ->setValue($contents);

        $this->getProperty(self::FIELD_PAGE_CONTENTS_RAW, $locale)
            ->setValue($contents);

        return $this;
    }

    /**
     * Возвращает URL поста.
     * @param bool $isAbsolute абсолютный ли URL
     * @return string
     */
    public function getPageUrl($isAbsolute = false)
    {
        switch ($this->publishStatus) {
            case self::POST_STATUS_DRAFT : {
                $handler = BlogPostCollection::HANDLER_DRAFT;
                break;
            }
            case self::POST_STATUS_REJECTED : {
                $handler = BlogPostCollection::HANDLER_REJECT;
                break;
            }
            case self::POST_STATUS_NEED_MODERATE : {
                if ($this->usersModule->getCurrentUser() === $this->author->profile) {
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
     * Выставляет статус поста черновик.
     * @return $this
     */
    public function draft()
    {
        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::POST_STATUS_DRAFT);
        return $this;
    }

    /**
     * Публикует пост.
     * @return $this
     */
    public function publish()
    {
        if ($this->publishStatus !== self::POST_STATUS_PUBLISHED) {
            if ($this->active && $this->author instanceof BlogAuthor) {
                $this->author->incrementPostCount();
            }

            $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::POST_STATUS_PUBLISHED);
        }

        return $this;
    }

    /**
     * Снимает пост с публикации и помещает его в черновики.
     * @param string $status статус снятого с публикации поста
     * @throws RuntimeException в случае если передан запрещённый или неизвестный статус публикации
     * @return $this
     */
    public function unPublish($status = self::POST_STATUS_DRAFT)
    {
        if (
            $this->active &&
            ($this->publishStatus === self::POST_STATUS_PUBLISHED) &&
            ($this->author instanceof BlogAuthor)
        ) {
            $this->author->decrementPostCount();
        }

        switch ($status) {
            case self::POST_STATUS_NEED_MODERATE : {
                $this->needModeration();
                break;
            }
            case self::POST_STATUS_REJECTED : {
                $this->reject();
                break;
            }
            case self::POST_STATUS_DRAFT : {
                $this->draft();
                break;
            }
            default: {
                throw new RuntimeException($this->translate(
                    '"{status}" is unknown or forbidden publish status.',
                    [
                        'status' => $status
                    ]
                ));
            }
        }

        return $this;
    }

    /**
     * Выставляет статус поста требует модерации.
     * @return $this
     */
    public function needModeration()
    {
        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::POST_STATUS_NEED_MODERATE);
        return $this;
    }

    /**
     * Выставляет статус поста отклонён.
     * @return $this
     */
    public function reject()
    {
        $this->getProperty(self::FIELD_PUBLISH_STATUS)->setValue(self::POST_STATUS_REJECTED);
        return $this;
    }

    /**
     * Увеличивает количество комментариев, опубликованных к посту.
     * @return $this
     */
    public function incrementCommentCount()
    {
        /** @var ICounterProperty $postCommentsCount */
        $postCommentsCount = $this->getProperty(self::FIELD_COMMENTS_COUNT);
        $postCommentsCount->increment();

        return $this;
    }

    /**
     * Уменьшает количество комментариев, опубликованных к посту.
     * @return $this
     */
    public function decrementCommentCount()
    {
        /** @var ICounterProperty $postCommentsCount */
        $postCommentsCount = $this->getProperty(self::FIELD_COMMENTS_COUNT);
        $postCommentsCount->decrement();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'model:blogPost';
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($role, $operationName, array $assertions)
    {
        if (!$role instanceof ComponentRoleProvider || !$role->getIdentity() instanceof RegisteredUser) {
            return false;
        }

        /**
         * @var RegisteredUser $user
         */
        $user = $role->getIdentity();
        $result = true;

        foreach ($assertions as $assertion) {
            switch($assertion) {
                case 'own': {
                    $result = $result && ($this->author->profile === $user);
                    break;
                }
                default: {
                return false;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function isInIndex()
    {
        return ($this->publishStatus === self::POST_STATUS_PUBLISHED) && $this->active && !$this->trashed;
    }

    /**
     * Мутатор для поля статус публикации.
     * @param string $value статус публикации
     * @return $this
     */
    public function changeStatus($value)
    {
        switch ($value) {
            case self::POST_STATUS_PUBLISHED : {
                $this->publish();
                break;
            }
            case self::POST_STATUS_NEED_MODERATE :
            case self::POST_STATUS_REJECTED :
            case self::POST_STATUS_DRAFT : {
                $this->unPublish($value);
                break;
            }
        }

        return $this;
    }

}
 