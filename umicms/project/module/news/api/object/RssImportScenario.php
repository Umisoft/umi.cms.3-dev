<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
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
class RssImportScenario extends CmsObject
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
