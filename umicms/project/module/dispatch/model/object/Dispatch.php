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
 * Рассылки.
 *
 */
class Dispatch extends CmsObject
{
    /**
     * Имя поля для хранения описания рассылки
     */
    const FIELD_DESCRIPTION = 'description';

    /**
     * Имя поля для хранения даты последней отправки писем
     */
    const FIELD_DATE_LAST_SENDING = 'date_last_sending';

    /**
     * Имя поля для хранения ленту новости
     */
    const FIELD_NEWS_LENT = 'date_last_sending';

    /**
     * Имя поля для хранения подписчиков
     */
    const FIELD_SUBSCRIBER = 'subscriber';

    /**
     * Имя поля для хранения отписанных подписчиков
     */
    const FIELD_UNSUBSCRIBER = 'unsubscriber';

    /**
     * Имя поля для хранения группа пользовтелей
     */
    const FIELD_GROUP_USER = 'usergroup';

    /**
     * Имя поля для хранения выпуски рассылок
     */
    const FIELD_RELEASE = 'release';

}
