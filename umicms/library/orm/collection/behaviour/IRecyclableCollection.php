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
use umicms\orm\object\behaviour\IRecyclableObject;
use umicms\orm\selector\CmsSelector;

/**
 * Интерфейс коллекций, поддерживающих удаление объектов в корзину.
 */
interface IRecyclableCollection extends ICmsCollection
{
    /**
     * Возвращает селектор для выбора объектов, помещеных в корзину.
     * @return CmsSelector|IRecyclableObject[]
     */
    public function selectTrashed();

    /**
     * Помещает объект в корзину.
     * @param IRecyclableObject $object
     * @return $this
     */
    public function trash(IRecyclableObject $object);

    /**
     * Извлекает объект из корзины.
     * @param IRecyclableObject $object
     * @return $this
     */
    public function untrash(IRecyclableObject $object);
}
 