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
 * Базовый класс Подписчиков.
 *
 */
class BaseSubscriber extends CmsObject
{
    /**
     * Имя поля для хранения E-mail'a
     */
    const FIELD_EMAIL = 'email';

    /**
     * Имя поля для хранения имени подписчика
     */
    const FIELD_FIRST_NAME = 'firstName';

    /**
     * Имя поля для хранения фамилии подписчика
     */
    const FIELD_LAST_NAME = 'lastName';

    /**
     * Имя поля для хранения отчества подписчика
     */
    const FIELD_MIDDLE_NAME = 'middleName';

    /**
     * Имя поля для хранения пола подписчика
     */
    const FIELD_SEX = 'sex';

    /**
     * Имя поля для хранения рассылок
     */
    const FIELD_DISPATCH = 'dispatch';

    /**
     * Имя поля для хранения отписанных рассылок
     */
    const FIELD_UNSUBSCRIBE_DISPATCHES = 'unsubscribe_dispatch';

    /**
     * Имя поля для хранения профиля подписчика
     */
    const FIELD_PROFILE = 'profile';

    /**
     * Имя поля для хранения причины отписки
     */
    const FIELD_REASON = 'reason';

}
