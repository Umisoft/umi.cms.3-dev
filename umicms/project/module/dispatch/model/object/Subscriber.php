<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\model\object;

use umicms\project\module\dispatch\model\collection\SubscriberCollection;
/**
 * Подписчики.
 *
 * @property string $email e-mail
 */
class Subscriber extends BaseSubscriber
{
    
    const TYPE_NAME = 'subscriber';


    /**
     * Форма подписки пользователя на сайте
     */
    const FORM_SUBSCRIBE_SITE = 'subscribeSite';


    /**
     * Проверяет валидность E-mail'a.
     * @param string E-mail подписчика
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
            $this->getProperty(BaseSubscriber::FIELD_EMAIL)->addValidationErrors(
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
        if (!$this->getValue(self::FIELD_DISPLAY_NAME, $localeId)) {
            $displayName = $this->getValue(self::FIELD_DISPLAY_NAME, $this->getCurrentLocale()) ?: $this->email;
            $this->setValue(self::FIELD_DISPLAY_NAME, $displayName, $localeId);
        }
    }
}