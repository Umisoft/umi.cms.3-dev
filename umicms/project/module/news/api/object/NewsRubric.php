<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
