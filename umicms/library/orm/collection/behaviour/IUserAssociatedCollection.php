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

use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\collection\ICmsCollection;
use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\project\module\users\model\object\BaseUser;

/**
 * Коллекция, связанных с пользователем объектов.
 */
interface IUserAssociatedCollection extends ICmsCollection
{

    /**
     * Заполняет объект, связанный с пользователем, значениями свойств пользователя
     * @param BaseUser $user связанный пользователь
     * @param IUserAssociatedObject $object заполняемый объект
     * @throws RuntimeException если объект не принадлежит коллекции
     * @return IUserAssociatedObject
     */
    public function fillFromUser(BaseUser $user, IUserAssociatedObject $object);

    /**
     * Возвращает объект, связанный с пользователем
     * @param BaseUser $user
     * @throws NonexistentEntityException если такого объекта не существует
     * @return IUserAssociatedObject
     */
    public function getByUser(BaseUser $user);

}
 