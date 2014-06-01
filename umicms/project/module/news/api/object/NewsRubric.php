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

use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsHierarchicObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Новостная рубрика.
 *
 * @property IObjectSet $news новости рубрики
 */
class NewsRubric extends CmsHierarchicObject implements ICmsPage
{
    /**
     *  Имя поля для хранения новостей
     */
    const FIELD_NEWS = 'news';

    use TCmsPage;

}
