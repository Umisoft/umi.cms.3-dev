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
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umicms\project\module\forum\model\collection\ForumMessageCollection;

/**
 * Конференция форума.
 *
 * @property IObjectSet $themes темы категории
 * @property int $themesCount количество тем в категории
 * @property int $messagesCount количество сообщений в категории
 */
class ForumConference extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения тем
     */
    const FIELD_THEMES = 'themes';
    /**
     * Имя поля для хранения количества тем в конференции
     */
    const FIELD_THEMES_COUNT = 'themesCount';
    /**
     * Имя поля для хранения количества сообщений в конференции
     */
    const FIELD_MESSAGES_COUNT = 'messagesCount';

    /**
     * Вычисляет количество опубликованных автором тем.
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
            ->where(ForumTheme::FIELD_CONFERENCE)
                ->equals($this)
            ->where(ForumTheme::FIELD_ACTIVE)
                ->equals(true)
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
        /**
         * @var ICalculableProperty $localizedThemesCountProperty
         */
        $localizedThemesCountProperty = $this->getProperty(self::FIELD_THEMES_COUNT);
        $localizedThemesCountProperty->recalculate();

        return $this;
    }
}
 