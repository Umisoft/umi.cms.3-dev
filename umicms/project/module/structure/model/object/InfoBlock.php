<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\structure\model\object;

/**
 * Информационный блок.
 *
 * @property string $phoneNumber номер телефона
 * @property string $email E-mail
 * @property string $address адрес
 * @property string $logo логотип
 * @property string $copyright копирайт
 * @property string $counter счётчик посещений
 * @property string $widgetVk виджет Вконтакте
 * @property string $widgetFacebook виджет FaceBook
 * @property string $widgetTwitter виджет Twitter
 * @property string $share виджет "Поделиться"
 * @property string $socialGroupLink ссылки на группы в социальных сетях
 */
class InfoBlock extends BaseInfoBlock
{
    const TYPE = 'infoblock';

    /**
     * Имя поля для хранения номера телефона
     */
    const FIELD_PHONE_NUMBER = 'phoneNumber';
    /**
     * Имя поля для хранения E-mail
     */
    const FIELD_EMAIL = 'email';
    /**
     * Имя поля для хранения адреса
     */
    const FIELD_ADDRESS = 'address';
    /**
     * Имя поля для хранения логотипа
     */
    const FIELD_LOGO = 'logo';
    /**
     * Имя поля для хранения копирайта
     */
    const FIELD_COPYRIGHT = 'copyright';
    /**
     * Имя поля для хранения счётчика посещений
     */
    const FIELD_COUNTER = 'counter';
    /**
     * Имя поля для хранения виджета Вконтакте
     */
    const FIELD_WIDGET_VK = 'widgetVk';
    /**
     * Имя поля для хранения виджета FaceBook
     */
    const FIELD_WIDGET_FACEBOOK = 'widgetFacebook';
    /**
     * Имя поля для хранения виджета Twitter
     */
    const FIELD_WIDGET_TWITTER = 'widgetTwitter';
    /**
     * Имя поля для хранения виджета "Поделиться"
     */
    const FIELD_SHARE = 'share';
    /**
     * Имя поля для хранения ссылок на группы в социальных сетях
     */
    const FIELD_SOCIAL_GROUP_LINK = 'socialGroupLink';
}
 