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

use umi\orm\object\IObject;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\model\object\BaseUser;

/**
 * Трейт для коллекции, связанных с пользователем объектов.
 */
trait TUserAssociatedCollection
{
    /**
     * Возвращает новый селектор для формирования выборки объектов коллекции.
     * @return CmsSelector
     */
    abstract public function select();
    /**
     * @see ICmsCollection::getName()
     */
    abstract public function getName();
    /**
     * @see ICmsCollection::contains()
     */
    abstract public function contains(IObject $object);

    /**
     * @see ILocalizable::translate()
     */
    abstract protected function translate($message, array $placeholders = [], $localeId = null);

    /**
     * @see IUserAssociatedCollection::fillFromUser()
     */
    public function fillFromUser(BaseUser $user, IUserAssociatedObject $object)
    {
        if (!$this->contains($object)) {
            throw new RuntimeException($this->translate(
                'Cannot fill object from user. Object from collection "{objectCollection}" does not belong to "{collection}".',
                [
                    'objectCollection' => $object->getCollectionName(),
                    'collection' => $this->getName()
                ]
            ));
        }

        if ($object->user) {
            return $object;
        }

        $object->user = $user;

        foreach ($user->getAllProperties() as $property) {

            $propertyName = $property->getName();
            $propertyLocaleId = $property->getLocaleId();
            $propertyValue = $user->getValue($propertyName, $propertyLocaleId);

            if ($propertyValue && $object->hasProperty($propertyName, $propertyLocaleId)) {

                $objectProperty = $object->getProperty($propertyName, $propertyLocaleId);
                if (!$objectProperty->getIsReadOnly() && !$object->getValue($propertyName, $propertyLocaleId)) {
                       $object->setValue($propertyName, $propertyValue, $propertyLocaleId);
                }
            }
        }

        return $object;
    }

    /**
     * @see IUserAssociatedCollection::getByUser()
     */
    public function getByUser(BaseUser $user)
    {
        $object = $this->select()
            ->where(IUserAssociatedObject::FIELD_USER)
            ->equals($user)
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$object instanceof IUserAssociatedObject) {
            throw new NonexistentEntityException(
                $this->translate(
                    'Cannot get object from collection "{collection}" for user with GUID "{guid}".',
                    ['guid' => $user->guid, 'collection' => $this->getName()]
                )
            );
        }

        return $object;
    }
}
 