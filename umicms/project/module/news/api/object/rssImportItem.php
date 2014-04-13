<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\api\object;

use umicms\orm\object\CmsObject;

/**
 * RSS.
 *
 * @property string $rssUrl URL RSS-ленты, которую необходимо импортировать
 * @property string $charsetRss кодировка RSS-канала
 * @property string $rubric имя поля для хранения рубрики, к которой относятся импортируемые новости
 * @property string $subjects имя поля для хранения сюжета, к которой относятся импортируемые новости
 */
class RssImportItem extends CmsObject
{
    /**
     * URL RSS-ленты, которую необходимо импортировать.
     */
    const FIELD_RSS_URL = 'rssUrl';
    /**
     * Кодировка RSS-канала.
     */
    const FIELD_CHARSET_RSS = 'charsetRss';
    /**
     * Имя поля для хранения рубрики, к которой относятся импортируемые новости.
     */
    const FIELD_RUBRIC = 'rubric';
    /**
     * Имя поля для хранения сюжета, к которой относятся импортируемые новости.
     */
    const FIELD_SUBJECTS = 'subjects';
}
