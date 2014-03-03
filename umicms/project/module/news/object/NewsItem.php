<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\news\object;

use DateTime;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Новость.
 *
 * @property string $metaKeywords мета-тег keywords
 * @property string $metaDescription мета-тег description
 * @property string $metaTitle мета-тег title
 * @property string $h1 заголовок
 * @property string $content содержание
 * @property string $announcement анонс
 * @property string $slug последней часть ЧПУ
 * @property DateTime $date дата
 * @property NewsRubric|null $rubric рубрика, к которой относится новость
 * @property IManyToManyObjectSet $subjects сюжеты, в которые входит новость
 */
class NewsItem extends CmsObject
{
    /**
     * Имя поля для хранения рубрики, к которой относится новость
     */
    const FIELD_RUBRIC = 'rubric';
    /**
     * Имя поля для хранения последней части ЧПУ новости
     */
    const FIELD_SLUG = 'slug';
    /**
     * Имя поля для хранения времени публикации новости
     */
    const FIELD_DATE = 'date';
    /**
     * Имя поля для хранения сюжетов новости
     */
    const FIELD_SUBJECTS = 'subjects';
    /**
     * Имя поля для хранения анонса новости
     */
    const FIELD_ANNOUNCEMENT = 'announcement';
}
 