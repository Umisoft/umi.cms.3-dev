<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\hmvc\component;

/**
 * Компонент административной панели.
 */
class AdminComponent extends BaseComponent
{
    /**
     * Имя опции для настроек интерфейса компонента в административной панели
     */
    const OPTION_ADMIN_INTERFACE = 'interface';
    /**
     * Имя опции для задания имени коллекции, управляемой компонентом
     */
    const OPTION_COLLECTION_NAME = 'collectionName';

}
 