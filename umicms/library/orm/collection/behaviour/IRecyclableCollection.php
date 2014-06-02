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
 