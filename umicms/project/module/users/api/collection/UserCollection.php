<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\api\collection;

use umi\orm\metadata\IObjectType;
use umicms\exception\NotAllowedOperationException;
use umicms\orm\collection\behaviour\IActiveAccessibleCollection;
use umicms\orm\collection\behaviour\ILockedAccessibleCollection;
use umicms\orm\collection\behaviour\IRecyclableCollection;
use umicms\orm\collection\behaviour\TActiveAccessibleCollection;
use umicms\orm\collection\behaviour\TLockedAccessibleCollection;
use umicms\orm\collection\behaviour\TRecyclableCollection;
use umicms\orm\collection\SimpleCollection;
use umicms\orm\object\behaviour\IActiveAccessibleObject;
use umicms\orm\object\behaviour\ILockedAccessibleObject;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\users\api\object\BaseUser;

/**
 * Коллекция для работы с пользователями.
 *
 * @method CmsSelector|BaseUser[] select() Возвращает селектор для выбора пользователей.
 * @method BaseUser get($guid, $withLocalization = false)  Возвращает пользователя по GUID.
 * @method BaseUser getById($objectId, $withLocalization = false) Возвращает пользователя по id.
 * @method BaseUser add($typeName = IObjectType::BASE) Создает и возвращает пользователя.
 */
class UserCollection extends SimpleCollection
    implements IRecyclableCollection, IActiveAccessibleCollection, ILockedAccessibleCollection
{
    use TRecyclableCollection;
    use TLockedAccessibleCollection;
    use TActiveAccessibleCollection {
        TActiveAccessibleCollection::deactivate as deactivateInternal;
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(IActiveAccessibleObject $object)
    {
        if ($object instanceof ILockedAccessibleObject && $object->locked) {
            throw new NotAllowedOperationException('Cannot deactivate locked user.');
        }

        return $this->deactivateInternal($object);
    }

}