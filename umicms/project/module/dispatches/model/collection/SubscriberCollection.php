<?php
/**
 * This file is part of UMI.CMS.
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\collection;

use umi\form\element\CheckboxGroup;
use umi\form\element\CSRF;
use umi\form\element\Text;
use umicms\form\element\Captcha;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\orm\collection\behaviour\IUserAssociatedCollection;
use umicms\orm\collection\behaviour\TUserAssociatedCollection;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\object\Subscriber;
use umicms\project\module\dispatches\model\object\Dispatch;
use umicms\project\module\users\model\object\BaseUser;

/**
 * Коллекция для работы с подписчиками.
 *
 * @method CmsSelector|Subscriber[] select() Возвращает селектор для выбора подписчиков.
 * @method Subscriber get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает подписчика по GUID.
 * @method Subscriber getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает подписчика по id.
 * @method Subscriber add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает подписчика.
 */
class SubscriberCollection extends CmsCollection implements IUserAssociatedCollection
{
    use TUserAssociatedCollection;

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param Subscriber $subscriber
     * @return bool
     */
    public function checkEmailUniqueness(Subscriber $subscriber)
    {
        $subscribers = $this->getInternalSelector()
            ->fields([Subscriber::FIELD_IDENTIFY])
            ->where(Subscriber::FIELD_EMAIL)
            ->equals($subscriber->email)
            ->where(Subscriber::FIELD_IDENTIFY)
            ->notEquals($subscriber->getId())
            ->getResult();

        return !count($subscribers);
    }

    /**
     * Возвращает подписчика по email
     * @param string $email email
     * @throws NonexistentEntityException если не существует подписчика с таким email
     * @return Subscriber $subscriber
     */
    public function getSubscriberByEmail($email)
    {
        $subscriber = $this->getInternalSelector()
            ->where(Subscriber::FIELD_EMAIL)
            ->equals($email)
            ->end()
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$subscriber instanceof Subscriber) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find subscriber by email.')
            );
        }

        return $subscriber;
    }

    /**
     * Создает и возвращает подписчика для пользователя
     * @param BaseUser $user пользователь
     * @param string $type тип создаваемого автора
     * @param null $guid GUID создаваемого автора
     * @return Subscriber
     */
    public function createForUser(BaseUser $user, $type = IObjectType::BASE, $guid = null)
    {
        $subscriber = $this->add($type, $guid);
        $this->fillFromUser($user, $subscriber);

        return $subscriber;
    }

}
