<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\survey\model\object;

use umicms\orm\object\CmsObject;


/**
 * Class Answer
 * @package umicms\project\module\survey\model\object
 * @property Survey|null $survey опрос
 * @property integer $counter количество голосов
 */
class Answer extends CmsObject
{
    /**
     * Имя поля для хранения опроса, к которому относится ответ
     */
    const FIELD_SURVEY = 'survey';

    /**
     * Имя поля для хранения количества голосов, отданных з данный ответ
     */
    const FIELD_COUNTER = 'counter';

}
