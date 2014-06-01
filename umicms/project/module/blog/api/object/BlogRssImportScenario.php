<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\blog\api\object;

use umi\orm\objectset\ManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Сценарий иморта внешней RSS ленты.
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
