<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use DateTime;
use umicms\orm\object\CmsObject;

/**
 * Базовый класс подписки.
 *
 * @property Dispatch $dispatch Связанная рассылка
 * @property Subscriber $subscriber Связанный подписчик
 * @property DateTime $unsubscribeTime дата и время отписки
 * @property string $reason причина отписки
 * @property Release $release выпуск рассылки
 */
class Unsubscription extends CmsObject
{
    /**
     * Тип объекта
     */
    const TYPE_NAME = 'unsubscription';

    /**
     * Имя поля для хранения рассылки
     */
    const FIELD_DISPATCH = 'dispatch';

    /**
     * Имя поля для хранения подписчика
     */
    const FIELD_SUBSCRIBER = 'subscriber';

    /**
     * Имя поля для хранения даты отписки
     */
    const FIELD_DATE_UNSUBSCRIBE = 'date_unsubscribe';

    /**
     * Имя поля для хранения причины отписки
     */
    const FIELD_REASON = 'reason';

    /**
     * Имя поля для хранения выпуска рассылки
     */
    const FIELD_RELEASE = 'release';

}
