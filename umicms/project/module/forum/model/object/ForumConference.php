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

use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Конференция форума.
 *
 * @property IObjectSet $themes темы категории
 */
class ForumConference extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения тем
     */
    const FIELD_THEMES = 'themes';
}
 