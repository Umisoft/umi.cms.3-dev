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
 * Рассылка.
 *
 * @property string $description описание
 * @property Release|null $lastRelease последний выпуск рассылки
 * @property IManyToManyObjectSet|Subscriber[] $subscribers подписчики на рассылку
 * @property IManyToManyObjectSet|Subscriber[] $unsubscribed отписавшиеся подписчики
 */
class Dispatch extends CmsObject
{
    /**
     * Имя поля для хранения описания рассылки
     */
    const FIELD_DESCRIPTION = 'description';
    /**
     * Имя поля для хранения последнего выпуска рассылки
     */
    const FIELD_LAST_RELEASE = 'lastRelease';
    /**
     * Имя поля для хранения подписчиков
     */
    const FIELD_SUBSCRIBERS = 'subscribers';
    /**
     * Имя поля для хранения отписавшихся подписчиков
     */
    const FIELD_UNSUBSCRIBED = 'unsubscribed';

}
