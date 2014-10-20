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
 * Причина отписки.
 *
 * @property Release $release выпуск рассылки, из-за которого произошла отписка
 * @property Subscriber $subscriber отписавшийся подписчик
 * @property DateTime $date дата отписки
 */
class Reason extends CmsObject
{
    /**
     *  Имя поля для хранения выпуск рассылки
     */
    const FIELD_RELEASE = 'release';
    /**
     *  Имя поля для хранения подписчика
     */
    const FIELD_SUBSCRIBER = 'subscriber';
    /**
     *  Имя поля для хранения даты отписки
     */
    const FIELD_DATE = 'date_unsubscribe';

}
