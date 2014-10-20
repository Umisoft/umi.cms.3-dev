<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatches\model\object;

use umi\orm\objectset\IManyToManyObjectSet;
use umicms\orm\object\behaviour\IUserAssociatedObject;
use umicms\orm\object\CmsObject;
use umicms\project\module\dispatches\model\collection\SubscriberCollection;

/**
 * Базовый класс подписчика.
 *
 * @property string $email email
 * @property string $firstName имя
 * @property string $lastName фамилия
 * @property string $middleName отчество
 * @property IManyToManyObjectSet|Dispatch[] $dispatches рассылки, на которые подписан подписчик
 * @property IManyToManyObjectSet|Dispatch[] $unsubscribedDispatches рассылки, от которых отписался подписчик
 * @property string $token токен для управления подписками
 */
class Subscriber extends CmsObject implements IUserAssociatedObject
{
    /**
     * Имя поля для хранения email
     */
    const FIELD_EMAIL = 'email';
    /**
     * Имя поля для хранения имени подписчика
     */
    const FIELD_FIRST_NAME = 'firstName';
    /**
     * Имя поля для хранения фамилии подписчика
     */
    const FIELD_LAST_NAME = 'lastName';
    /**
     * Имя поля для хранения отчества подписчика
     */
    const FIELD_MIDDLE_NAME = 'middleName';
    /**
     * Имя поля для хранения рассылок
     */
    const FIELD_DISPATCHES = 'dispatches';
    /**
     * Имя поля для хранения отписанных рассылок
     */
    const FIELD_UNSUBSCRIBED_DISPATCHES = 'unsubscribedDispatches';
    /**
     * Имя поля для хранения token'а
     */
    const FIELD_TOKEN = 'token';

    /**
     * Форма подписки
    */
    const FORM_SUBSCRIBE_SITE = 'formSubscribe';

    /**
     * Форма подписки
    */
    const FORM_UNSUBSCRIBE_SITE = 'formUnsubscribe';

    /**
     * Проверяет валидность email.
     * @return bool
     */
    public function validateEmail()
    {
        $result = true;

        /**
         * @var SubscriberCollection $collection
         */
        $collection = $this->getCollection();

        if (!$collection->checkEmailUniqueness($this)) {
            $result = false;
            $this->getProperty(Subscriber::FIELD_EMAIL)
                ->addValidationErrors(
                    [$this->translate('Email is not unique')]
                );
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function fillProperties()
    {
        $this->generateDisplayName($this->getCurrentDataLocale());
    }

    /**
     * Генерирует отображаемое имя, если оно не было установлено.
     * @param string|null $localeId
     * @return bool
     */
    protected function generateDisplayName($localeId = null)
    {
        $this->setValue(self::FIELD_DISPLAY_NAME, $this->email, $localeId);
    }
}
