<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\orm\collection\behaviour;

use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\selector\CmsSelector;

/**
 * Интерфейс коллекций, поддерживающих управлению активностью объекта на сайте.
 */
interface IActiveAccessibleCollection extends ICmsCollection
{
    /**
     * Возвращает селектор для выбора только активных объектов
     * @return CmsSelector|IActiveAccessibleObject[]
     */
    public function selectActive();

    /**
     * Возвращает селектор для выбора только не активных объектов
     * @return CmsSelector|IActiveAccessibleObject[]
     */
    public function selectInactive();

    /**
     * Делает объект активным.
     * @param IActiveAccessibleObject $object
     * @return $this
     */
    public function activate(IActiveAccessibleObject $object);

    /**
     * Делает объект не активным.
     * @param IActiveAccessibleObject $object
     * @return $this
     */
    public function deactivate(IActiveAccessibleObject $object);
}
 