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

use umi\orm\objectset\ManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Сценарий иморта внешней RSS ленты.
 *
 * @property string $rssUrl Абсолютный URL внешней RSS-ленты.
 * @property NewsRubric|null $rubric рубрика, которая будет установлена импортируемым новостям
 * @property ManyToManyObjectSet|NewsSubject[] $subjects список сюжетов, которые будут установлены импортируемым новостям
 */
class NewsRssImportScenario extends CmsObject
{
    /**
     * URL RSS-ленты, которую необходимо импортировать.
     */
    const FIELD_RSS_URL = 'rssUrl';
    /**
     * Имя поля для хранения рубрики, к которой относятся импортируемые новости.
     */
    const FIELD_RUBRIC = 'rubric';
    /**
     * Имя поля для хранения сюжета, к которой относятся импортируемые новости.
     */
    const FIELD_SUBJECTS = 'subjects';
}
