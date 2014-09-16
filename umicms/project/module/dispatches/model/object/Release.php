<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use DateTime;
use umicms\orm\object\CmsObject;

/**
 * Выпуски рассылок.
 *
 * @property Dispatch $dispatch рассылка
 * @property string $subject тема письма
 * @property string $header заголовок письма
 * @property string $message текст письма
 * @property Template $template шаблон письма
 * @property ReleaseStatus $status статус отправки
 * @property DateTime $startTime время начала отправки писем
 * @property DateTime $finishTime время окончания отправки писем
 * @property int $sentMessageCount количество отправленных писем
 * @property int $viewedMessageCount количество открытых писем
 * @property int $unsubscriptionCount количество отписок
 * @property int $viewPercent процент прочтений
 */
class Release extends CmsObject
{
    /**
     *  Имя поля для хранения рассылок
     */
    const FIELD_DISPATCH = 'dispatch';
    /**
     *  Имя поля для хранения темы выпуска
     */
    const FIELD_SUBJECT = 'subject';
    /**
     *  Имя поля для хранения заголовка письма
     */
    const FIELD_HEADER = 'header';
    /**
     *  Имя поля для хранения текста письма
     */
    const FIELD_MESSAGE = 'message';
    /**
     *  Имя поля для хранения шаблона письма
     */
    const FIELD_TEMPLATE = 'template';
    /**
     *  Имя поля для хранения статуса отправки
     */
    const FIELD_STATUS = 'status';
    /**
     *  Имя поля для хранения времени начала отправки писем
     */
    const FIELD_START_TIME = 'startTime';
    /**
     *  Имя поля для хранения времени окончания отправки писем
     */
    const FIELD_FINISH_TIME = 'finishTime';
    /**
     *  Имя поля для хранения количества отправленных писем
     */
    const FIELD_SENT_MESSAGE_COUNT = 'sentMessageCount';
    /**
     *  Имя поля для хранения количества открытых писем
     */
    const FIELD_VIEWED_MESSAGE_COUNT = 'viewedMessageCount';
    /**
     *  Имя поля для хранения количества отписок
     */
    const FIELD_UNSUBSCRIPTION_COUNT = 'unsubscriptionCount';
    /**
     *  Имя поля для хранения процента прочтений
     */
    const FIELD_VIEW_PERCENT = 'viewPercent';

    /**
     * Вычисляет процент прочтений писем выпуска.
     * @return float
     */
    public function calculateViewPercent()
    {
        if (!$this->sentMessageCount) {
            return 0;
        }

        return ($this->viewedMessageCount / $this->sentMessageCount) * 100;
    }

}
