<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\surveys\model\object;

use umicms\orm\object\CmsObject;
use umicms\orm\object\ICmsPage;
use umicms\orm\object\TCmsPage;
use umi\orm\objectset;


/**
 * Class Survey
 * @package umicms\project\module\surveys\model\object
 * @property $answers ответы опроса
 * @property boolean $multipleChoice возможность множественного выбора
 */
class Survey extends CmsObject implements ICmsPage
{
    use TCmsPage;

    /**
     *  Имя поля для связки с ответами
     */
    const FIELD_ANSWERS = 'answers';
    /**
     * Имя поля для указания возможности множественного выбора ответов
     */
    const FIELD_MULTIPLE_CHOICE = 'multipleChoice';
    /**
     * Форма голосования
     */
    const FORM_VOTE = 'vote';
    /**
     * Тип объекта
     */
    const TYPE = 'survey';

}
