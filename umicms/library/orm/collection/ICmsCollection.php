<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\orm\collection;

use umi\form\IForm;
use umi\form\IFormAware;
use umi\i18n\ILocalesAware;
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
interface ICmsCollection extends ICollection, ILocalizable, ICollectionManagerAware, IFormAware, ILocalesAware
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
     * Возвращает список имен словарей для перевода лейблов коллекции.
     * @return array
     */
    public function getDictionaryNames();

    /**
     * Возвращает список имен типов, доступных для создания.
     * @return array
     */
    public function getCreateTypeList();

    /**
     * Возвращает список имен типов, объекты которых доступны для редактирования.
     * @return array
     */
    public function getEditTypeList();
}
