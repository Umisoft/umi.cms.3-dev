<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\CmsObject;

/**
 * Лог рассылки.
 *
 * @property Release|null $release выпуск рассылки
 * @property IManyToManyObjectSet|Subscriber[] $subscriber подписчик
 * @property bool $read признак того, что подписчик прочитал рассылку
 * @property bool $send признак того, что письмо было отправлено подписчику
 */
class Logdispatch extends CmsObject
{
    /**
     * Имя поля для хранения выпуска рассылки
     */
    const FIELD_RELEASE = 'release';
    /**
     * Имя поля для хранения подписчика
     */
    const FIELD_SUBSCRIBERS = 'subscriber';
    /**
     * Имя поля для хранения состояния прочтения
     */
    const FIELD_READ = 'read';
    /**
     * Имя поля для хранения состояния отправки
     */
    const FIELD_SENT = 'sent';

}
