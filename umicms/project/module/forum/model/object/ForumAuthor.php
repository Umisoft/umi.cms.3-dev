<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\forum\model\object;

use umi\orm\metadata\field\relation\HasManyRelationField;
use umi\orm\metadata\IObjectType;
use umi\orm\object\property\calculable\ICalculableProperty;
use umi\orm\objectset\IObjectSet;
use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\forum\model\collection\ForumMessageCollection;

/**
 * Автор сообщения.
 *
 * @property IObjectSet $messages сообщения автора
 * @property IObjectSet $themes темы автора
 * @property string $contentsRaw необработанный контент
 * @property int $messagesCount количество сообщений
 * @property int $themesCount количество тем
 */
class ForumAuthor extends CmsObject implements ICmsPage, IUserAssociatedObject
{
    use TCmsPage;

    /**
     * Имя поля для хранения необработанного контента
     */
    const FIELD_PAGE_CONTENTS_RAW = 'contentsRaw';
    /**
     * Имя поля для хранения сообщений автора
     */
    const FIELD_MESSAGES = 'messages';
    /**
     * Имя поля для хранения тем автора
     */
    const FIELD_THEMES = 'themes';
    /**
     * Имя поля для хранения количества сообщений написаных автором
     */
    const FIELD_MESSAGES_COUNT = 'messagesCount';
    /**
     * Имя поля для хранения количества тем созданных автором
     */
    const FIELD_THEMES_COUNT = 'themesCount';
    /**
     * Форма редактирования профиля автора
     */
    const FORM_EDIT_PROFILE = 'editProfile';

    /**
     * Метод мутатор для контентного поля.
     * @param string $contents контент профиля автора
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
     * Вычисляет количество опубликованных автором сообщений.
     * @return int
     */
    public function calculateMessagesCount()
    {
        /**
         * @var HasManyRelationField $messagesField
         */
        $messagesField = $this->getProperty(self::FIELD_MESSAGES)->getField();
        /**
         * @var ForumMessageCollection $messagesCollection
         */
        $messagesCollection = $messagesField->getTargetCollection();

        return $messagesCollection->getInternalSelector()
            ->fields([ForumMessage::FIELD_IDENTIFY])
            ->types([ForumMessage::TYPE_NAME . '*'])
            ->where(ForumMessage::FIELD_AUTHOR)
                ->equals($this)
            ->where(ForumMessage::FIELD_TRASHED)
                ->equals(false)
            ->getTotal();
    }

    /**
     * Помечает количество сообщений для пересчета.
     * @return $this
     */
    public function recalculateMessagesCount()
    {
        $messagesCountProperty = $this->getProperty(self::FIELD_MESSAGES_COUNT);
        foreach ($messagesCountProperty->getField()->getLocalizations() as $localeId => $localeInfo) {
            /**
             * @var ICalculableProperty $localizedMessagesCountProperty
             */
            $localizedMessagesCountProperty = $this->getProperty(self::FIELD_MESSAGES_COUNT, $localeId);
            $localizedMessagesCountProperty->recalculate();
        }

        return $this;
    }

    /**
     * Вычисляет количество опубликованных автором сообщений.
     * @return int
     */
    public function calculateThemesCount()
    {
        /**
         * @var HasManyRelationField $themesField
         */
        $themesField = $this->getProperty(self::FIELD_THEMES)->getField();
        /**
         * @var ForumMessageCollection $themesCollection
         */
        $themesCollection = $themesField->getTargetCollection();

        return $themesCollection->getInternalSelector()
            ->fields([ForumTheme::FIELD_IDENTIFY])
            ->types([IObjectType::BASE . '*'])
            ->where(ForumTheme::FIELD_AUTHOR)
                ->equals($this)
            ->where(ForumTheme::FIELD_TRASHED)
                ->equals(false)
            ->getTotal();
    }

    /**
     * Помечает количество тем для пересчета.
     * @return $this
     */
    public function recalculateThemesCount()
    {
        $themesCountProperty = $this->getProperty(self::FIELD_THEMES_COUNT);
        foreach ($themesCountProperty->getField()->getLocalizations() as $localeId => $localeInfo) {
            /**
             * @var ICalculableProperty $localizedThemesCountProperty
             */
            $localizedThemesCountProperty = $this->getProperty(self::FIELD_THEMES_COUNT, $localeId);
            $localizedThemesCountProperty->recalculate();
        }

        return $this;
    }
}
 