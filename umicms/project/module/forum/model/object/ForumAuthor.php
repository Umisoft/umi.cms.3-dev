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

use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Автор сообщения.
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
}
 