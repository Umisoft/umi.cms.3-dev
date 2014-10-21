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

use umicms\orm\object\CmsObject;

/**
 * Базовый класс подписки.
 *
 * @property Dispatch $dispatch Связанная рассылка
 * @property Subscriber $subscriber Связанный подписчик
 * @property string $token токен для управления подписками
 */
class Subscription extends CmsObject
{
    /**
     * Тип объекта
     */
    const TYPE_NAME = 'subscription';

    /**
     * Имя поля для хранения рассылки
     */
    const FIELD_DISPATCH = 'dispatch';

    /**
     * Имя поля для хранения подписчика
     */
    const FIELD_SUBSCRIBER = 'subscriber';

    /**
     * Имя поля для хранения токена
     */
    const FIELD_TOKEN = 'token';

}
