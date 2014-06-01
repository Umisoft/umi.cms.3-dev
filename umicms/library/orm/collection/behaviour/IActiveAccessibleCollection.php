<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
 