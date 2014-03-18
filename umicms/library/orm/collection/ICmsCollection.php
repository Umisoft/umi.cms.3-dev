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
use umi\orm\collection\ICollection;
use umicms\exception\NonexistentEntityException;
use umicms\exception\OutOfBoundsException;
use umicms\orm\object\CmsObject;

/**
 * Интерфейс коллекции объектов UMI.CMS
 */
interface ICmsCollection extends ICollection, IFormAware
{

    /**
     * Имя формы для редактирования объектов по умолчанию
     */
    const FORM_EDIT = 'edit';

    /**
     * Возвращает форму для типа объектов коллекции.
     * @param string $typeName имя типа
     * @param string $formName имя формы
     * @param CmsObject $object объект, для которого создается форма
     * @throws NonexistentEntityException если форма не зарегистрирована
     * @return IForm
     */
    public function getForm($typeName, $formName, CmsObject $object = null);

    /**
     * Проверяет, зарегистрирована ли форма для типа объектов коллекции.
     * @param string $typeName имя типа
     * @param string $formName имя формы
     * @return bool
     */
    public function hasForm($typeName, $formName);

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
