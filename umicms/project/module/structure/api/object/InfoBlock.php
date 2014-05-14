<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\structure\api\object;

/**
 * Информационный блок.
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
    const FIELD_WIDGET_FB = 'widgetFb';
    /**
     * Имя поля для хранения виджета Twitter
     */
    const FIELD_WIDGET_TW = 'widgetTw';
    /**
     * Имя поля для хранения виджета "Поделиться"
     */
    const FIELD_SHARE = 'share';
    /**
     * Имя поля для хранения ссылок на группы в социальных сетях
     */
    const FIELD_SOC_GROUP_LINK = 'socGroupLink';
}
 