<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection;

use umi\form\IForm;
use umi\form\IFormAware;
use umi\i18n\ILocalizable;
use umi\orm\collection\ICollection;
use umi\orm\collection\ICollectionManagerAware;
use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\exception\OutOfBoundsException;
use umicms\orm\object\ICmsObject;

/**
 * Интерфейс коллекции объектов UMI.CMS
 */
interface ICmsCollection extends ICollection, ILocalizable, ICollectionManagerAware, IFormAware
{
    /**
     * Имя формы для редактирования объектов по умолчанию
     */
    const FORM_EDIT = 'edit';
    /**
     * Имя формы для создания объектов по умолчанию
     */
    const FORM_CREATE = 'create';
    /**
     * Компонент обработчик коллекций в сайтовой панеле
     */
    const HANDLER_SITE = 'site';
    /**
     * Компонент обработчик коллекций в административной части
     */
    const HANDLER_ADMIN = 'admin';

    /**
     * Возвращает тип коллекции.
     * @return string
     */
    public function getType();

    /**
     * Возвращает форму для типа объектов коллекции.
     * @param string $formName имя формы
     * @param string $typeName имя типа
     * @param ICmsObject $object объект, для которого создается форма
     * @throws NonexistentEntityException если форма не зарегистрирована
     * @return IForm
     */
    public function getForm($formName, $typeName = IObjectType::BASE, ICmsObject $object = null);

    /**
     * Проверяет, зарегистрирована ли форма для типа объектов коллекции.
     * @param string $formName имя формы
     * @param string $typeName имя типа
     * @return bool
     */
    public function hasForm($formName, $typeName = IObjectType::BASE);

    /**
     * Возвращает путь к компоненту, обрабатывающему коллекцию.
     * @param string $applicationName имя приложения
     * @throws OutOfBoundsException если обработчик не зарегистрирован
     * @param string
     */
    public function getHandlerPath($applicationName);

    /**
     * Возвращает список компонентов-обработчиков.
     * @return array
     */
    public function getHandlerList();

    /**
     * Проверяет, есть ли обработчик у коллекции для указанного приложения.
     * @param string $applicationName имя приложения
     * @return bool
     */
    public function hasHandler($applicationName);

    /**
     * Возвращает список имен словарей для перевода лейблов коллекции
     * @return array
     */
    public function getDictionaryNames();
}
