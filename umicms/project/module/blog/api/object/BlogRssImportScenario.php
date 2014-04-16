<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\blog\api\object;

use umi\orm\objectset\ManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * RSS.
 *
 * @property string $rssUrl URL RSS-ленты, которую необходимо импортировать
 * @property BlogCategory|null $category имя поля для хранения категории, к которой относятся импортируемые посты
 * @property ManyToManyObjectSet|BlogTag[] $tags список тэгов, к которым относятся импортируемые посты
 */
class BlogRssImportScenario extends CmsObject
{
    /**
     * URL RSS-ленты, которую необходимо импортировать.
     */
    const FIELD_RSS_URL = 'rssUrl';
    /**
     * Имя поля для хранения категории, к которой относятся импортируемые посты.
     */
    const FIELD_CATEGORY = 'category';
    /**
     * Имя поля для хранения тэгов, к которым относятся импортируемые посты.
     */
    const FIELD_TAGS = 'tags';
}
