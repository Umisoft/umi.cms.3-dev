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

use umicms\orm\object\behaviour\IRecoverableObject;
use umicms\orm\object\CmsHierarchicObject;

/**
 * Базовый класс темы конференции.
 *
 * @property ForumConference $conference конференция, к которой относится тема
 */
abstract class BaseForumTheme extends CmsHierarchicObject implements IRecoverableObject
{
    /**
     * Имя поля для хранения конференции, к которой относится тема
     */
    const FIELD_CONFERENCE = 'conference';

    public function setConference($value)
    {
        $this->getProperty(self::FIELD_CONFERENCE)->setValue($value);

        return $this;
    }
}
 