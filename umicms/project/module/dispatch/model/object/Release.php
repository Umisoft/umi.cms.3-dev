<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\model\object;

use umi\orm\objectset\IObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Выпуски рассылок.
 *
 */
class Release extends CmsObject
{
    /**
     *  Имя поля для хранения рассылок
     */
    const FIELD_DISPATCHES = 'dispatch';

    /**
     *  Имя поля для хранения темы рассылок
     */
    const FIELD_SUBJECT = 'subject';

    /**
     *  Имя поля для хранения заголовок письма
     */
    const FIELD_MESSAGE_HEADER = 'message_header';

    /**
     *  Имя поля для хранения текста письма
     */
    const FIELD_MESSAGE = 'message';

    /**
     *  Имя поля для хранения шаблона письма
     */
    const FIELD_TEMPLATE_MESSAGE = 'template_message';

    /**
     *  Имя поля для хранения статус отправки
     */
    const FIELD_SENDING_STATUS = 'sending_status';

    /**
     *  Имя поля для хранения даты запуска
     */
    const FIELD_DATE_START = 'date_start';

    /**
     *  Имя поля для хранения даты завершения
     */
    const FIELD_DATE_FINISH = 'date_finish';

    /**
     *  Имя поля для хранения количества отправленных писем
     */
    const FIELD_COUNT_SEND_MESSAGE = 'count_send_message';

    /**
     *  Имя поля для хранения количества просмотров
     */
    const FIELD_COUNT_VIEWS = 'count_views';

    /**
     *  Имя поля для хранения количества отписок
     */
    const FIELD_COUNT_UNSUBSCRIBE = 'count_unsubscribe';

    /**
     *  Имя поля для хранения процент прочтений
     */
    const FIELD_PERCENT_READS = 'percent_reads';

    /**
     *  Имя поля для хранения причин отписок
     */
    const FIELD_REASON = 'reason';

    public function calculatePercentViews(){
        $countSendMessageValue = $this->getProperty(self::FIELD_COUNT_SEND_MESSAGE)->getValue();

        if($countSendMessageValue){
            $countViewsFieldValue = $this->getProperty(self::FIELD_COUNT_VIEWS)->getValue();
            $percentReads = ($countViewsFieldValue/$countSendMessageValue) * 100;
        } else $percentReads = 0;

        return $percentReads;
    }

}
