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

use DateTime;
use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;

/**
 * Новость.
 *
 * @property string $announcement анонс
 * @property DateTime $date дата
 * @property NewsRubric|null $rubric рубрика, к которой относится новость
 * @property IManyToManyObjectSet $subjects сюжеты, в которые входит новость
 * @property string $source источник публикации
 */
class NewsItem extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     * Имя поля для хранения рубрики, к которой относится новость
     */
    const FIELD_RUBRIC = 'rubric';
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
    /**
     * Источник публикации
     */
    const FIELD_SOURCE = 'source';


}
