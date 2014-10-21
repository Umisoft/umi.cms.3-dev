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

/**
 * Вариант ответа для опроса.
 *
 * @property Survey|null $survey опрос
 * @property int $votes количество голосов
 */
class Answer extends CmsObject
{
    /**
     * Имя поля для хранения опроса, к которому относится ответ
     */
    const FIELD_SURVEY = 'survey';
    /**
     * Имя поля для хранения количества голосов, отданных за данный ответ
     */
    const FIELD_VOTES = 'votes';

    /**
     * Выставляет опрос, к которому относится вариант ответа.
     * @param Survey $survey опрос
     * @return $this
     */
    public function setSurvey(Survey $survey)
    {
        if ($this->survey !== $survey) {
            $this->getProperty(self::FIELD_SURVEY)->setValue($survey);
            $this->getProperty(self::FIELD_VOTES)->setValue(0);
        }

        return $this;
    }
}
