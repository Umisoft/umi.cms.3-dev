<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\news\api\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Новостной сюжет.
 *
 * @property IManyToManyObjectSet $news новости сюжета
 * @property IManyToManyObjectSet $rss RSS-ленты сюжета
 */
class NewsSubject extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     *  Имя поля для хранения новостей
     */
    const FIELD_NEWS = 'news';
    /**
     * Имя поля для хранения RSS-ленты
     */
    const FIELD_RSS = 'rss';
}
