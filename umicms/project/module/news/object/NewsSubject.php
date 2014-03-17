<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;

/**
 * Новостной сюжет.
 *
 * @property IManyToManyObjectSet $news новости рубрики
 */
class NewsSubject extends CmsObject implements ICmsPage
{
    /**
     *  Имя поля для хранения новостей
     */
    const FIELD_NEWS = 'news';
}
