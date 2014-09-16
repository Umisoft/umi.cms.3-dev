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
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umicms\exception\NonexistentEntityException;
use umicms\form\element\Captcha;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatches\model\object\BaseSubscriber;
use umicms\project\module\dispatches\model\object\Dispatch;

/**
 * Коллекция для работы с подписчиками.
 *
 * @method CmsSelector|BaseSubscriber[] select() Возвращает селектор для выбора подписчиков.
 * @method BaseSubscriber get($guid, $localization = ILocalesService::LOCALE_CURRENT)  Возвращает подписчика по GUID.
 * @method BaseSubscriber getById($objectId, $localization = ILocalesService::LOCALE_CURRENT) Возвращает подписчика по id.
 * @method BaseSubscriber add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает подписчика.
 */
class SubscriberCollection extends CmsCollection
{

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param BaseSubscriber $subscriber
     * @return bool
     */
    public function checkEmailUniqueness(BaseSubscriber $subscriber)
    {
        $subscribers = $this->getInternalSelector()
            ->fields([BaseSubscriber::FIELD_IDENTIFY])
            ->where(BaseSubscriber::FIELD_EMAIL)
            ->equals($subscriber->email)
            ->getResult();

        return !count($subscribers);
    }

    /**
     * Возвращает подписчика по email
     * @param string $email email
     * @throws NonexistentEntityException если не существует подписчика с таким email
     * @return BaseSubscriber $subscriber
     */
    public function getSubscriberByEmail($email)
    {
        $subscriber = $this->getInternalSelector()
            ->where(BaseSubscriber::FIELD_EMAIL)
            ->equals($email)
            ->end()
            ->limit(1)
            ->getResult()
            ->fetch();

        if (!$subscriber instanceof BaseSubscriber) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find subscriber by email.')
            );
        }

        return $subscriber;
    }

    /**
     * Получить форму на подписку
     * @param BaseSubscriber $subscriber подписчик
     * @param bool $isAuth - авторизован или нет
     * @param CmsSelector|Dispatch[] $dispatches - рассылки
     * @return \umi\form\IForm
     */
    public function getSubscribeForm(BaseSubscriber $subscriber, $isAuth = false, CmsSelector $dispatches)
    {
        /**
         * @var array $config
         */
        $config = [
            'options'    => [
                'dictionaries' => [
                    'project.site.dispatches'
                ],
            ],
            'attributes' => [
                'method' => 'post'
            ],
            'elements'   => []
        ];

        if (count($dispatches)) {
            $config['elements'][BaseSubscriber::FIELD_DISPATCHES] = [
                'type'    => CheckboxGroup::TYPE_NAME,
                'options' => [
                    'choices' => []
                ]
            ];
            /**
             * @var array $arrayTemp
             */
            $arrayTemp = [];
            foreach ($dispatches as $dispatchItem) {
                $arrayTemp[$dispatchItem->getId()] = $dispatchItem->getProperty(Dispatch::FIELD_DISPLAY_NAME)
                    ->getValue();
            };
            $config['elements'][BaseSubscriber::FIELD_DISPATCHES]['options']['choices'] = $arrayTemp;
            unset($arrayTemp);
        }

        if (!$isAuth) {
            $config['elements'][BaseSubscriber::FIELD_EMAIL] = [
                'type'    => Text::TYPE_NAME,
                'label'   => BaseSubscriber::FIELD_EMAIL,
                'options' => [
                    'options' => [
                        'dataSource' => BaseSubscriber::FIELD_EMAIL
                    ]
                ]
            ];
        }

        $config['elements']['captcha'] = [
            'type' => Captcha::TYPE_NAME
        ];
        $config['elements']['csrf'] = [
            'type' => CSRF::TYPE_NAME
        ];

        $config['elements']['submit'] = [
            'type'  => 'submit',
            'label' => 'Submit'
        ];

        return $this->createForm($config, $subscriber);
    }

}
