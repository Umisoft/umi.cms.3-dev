<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\api\object;

use umi\acl\IAclAssertionResolver;
use umi\acl\IAclResource;
use umi\hmvc\acl\ComponentRoleProvider;
use umicms\project\module\users\api\UsersModule;

/**
 * Комментарий к посту.
 *
 * @property UsersModule $author автор поста
 * @property string $contents комментарий
 * @property string $publishStatus статус публикации комментария
 */
class BlogComment extends BlogBaseComment implements IAclResource, IAclAssertionResolver
{
    /**
     * Тип объекта
     */
    const TYPE = 'comment';
    /**
     * Имя поля для хранения автора поста
     */
    const FIELD_AUTHOR = 'author';
    /**
     * Имя поля для хранения комментария
     */
    const FIELD_CONTENTS = 'contents';
    /**
     * Имя поля для хранения необработанного контента комментария
     */
    const FIELD_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения статуса публикации комментария
     */
    const FIELD_PUBLISH_STATUS = 'publishStatus';
    /**
     * Форма добавления комментария
     */
    const FORM_ADD_COMMENT = 'addComment';
    /**
     * Форма публикации комментария
     */
    const FORM_PUBLISH_COMMENT = 'publish';
    /**
     * Форма отклонения комментария
     */
    const FORM_REJECT_COMMENT = 'reject';
    /**
     * Статус комментария: опубликован
     */
    const COMMENT_STATUS_PUBLISHED = 'published';
    /**
     * Статус комментария: отклонён
     */
    const COMMENT_STATUS_REJECTED = 'rejected';
    /**
     * Статус комментария: требует модерации
     */
    const COMMENT_STATUS_NEED_MODERATE = 'moderate';

    /**
     * Мутатор для контентного поля.
     * @param string $contents контент комментария
     * @param string $locale локаль
     * @return $this
     */
    public function setContents($contents, $locale)
    {
        $this->getProperty(self::FIELD_CONTENTS, $locale)
            ->setValue($contents);

        $this->getProperty(self::FIELD_CONTENTS_RAW, $locale)
            ->setValue($contents);

        return $this;
    }

    /**
     * Выставляет статус комментария опубликован.
     * @return $this
     */
    public function published()
    {
        $this->publishStatus = self::COMMENT_STATUS_PUBLISHED;
        return $this;
    }

    /**
     * Выставляет статус поста требует модерации.
     * @return $this
     */
    public function needModerate()
    {
        $this->publishStatus = self::COMMENT_STATUS_NEED_MODERATE;
        return $this;
    }

    /**
     * Выставляет статус комментария отклонён.
     * @return $this
     */
    public function rejected()
    {
        $this->publishStatus = self::COMMENT_STATUS_REJECTED;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAclResourceName()
    {
        return 'model:blogComment';
    }

    /**
     * {@inheritdoc}
     */
    public function isAllowed($role, $operationName, array $assertions)
    {
        if (!$role instanceof ComponentRoleProvider) {
            return false;
        }

        foreach ($assertions as $assertion) {
            if ($assertion === 'premoderation') {
                return false;
            }
        }

        return false;
    }
}
 