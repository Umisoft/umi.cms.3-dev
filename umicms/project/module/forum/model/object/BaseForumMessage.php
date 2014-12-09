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

use DateTime;
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Базовый класс сообщения форума.
 *
 * @property ForumMessage $post тема, к которой относится сообщение
 * @property DateTime $publishTime дата и время публикации сообщения
 */
class BaseForumMessage extends CmsHierarchicObject implements IRecyclableObject
{
    /**
     * Имя поля для хранения темы, к которой относится сообщение
     */
    const FIELD_THEME = 'theme';
    /**
     * Имя поля для хранения даты и времени публикации сообщения
     */
    const FIELD_PUBLISH_TIME = 'publishTime';

    /**
     * Изменяет тему сообщения.
     * @param ForumMessage|null $value сообщение
     * @return $this
     */
    public function setTheme($value)
    {
        $this->getProperty(self::FIELD_THEME)->setValue($value);

        return $this;
    }
}
