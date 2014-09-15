<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\dispatch\model\collection;

use umi\form\element\Hidden;
use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\BaseSubscriber;
use umicms\project\module\dispatch\model\object\Dispatch;
use umicms\exception\NonexistentEntityException;
use umi\filter\IFilterFactory;
use umi\validation\IValidatorFactory;
use umi\form\element\CheckboxGroup;
use umi\form\element\CSRF;
use umi\form\element\Text;
use umicms\form\element\Captcha;

/**
 * Коллекция для работы с подписчиками.
 *
 */
class SubscriberCollection extends CmsCollection
{

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param Subscriber $subscriber
     * @return bool
     */
    public function checkEmailUniqueness(Subscriber $subscriber)
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
     * @return Subscriber $subscriber
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

        if (!$subscriber instanceof Subscriber) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find subscriber by email.')
            );
        }

        return $subscriber;
    }


    /**
     * Получить форму на подписку
     * @param Subscriber $subscriber подписчик
     * @param bool $isAuth - авторизован или нет
     * @param $dispatch - рассылки
     * @return \umi\form\IForm
     */
    public function getSubscribeForm(Subscriber $subscriber, $isAuth = false, $dispatch)
    {
        /**
         * @var array $config
         */
        $config = [
            'options' => [
                'dictionaries' => [
                    'project.site.dispatch'
                ],
            ],
            'attributes' => [
                'method' => 'post'
            ],
            'elements' => []
        ];

        if(!empty($dispatch)){
            /**
             * @var Dispatch $dispatchItem
             */
            $config['elements'][BaseSubscriber::FIELD_DISPATCH] = [
                'type' => CheckboxGroup::TYPE_NAME,
                'options' => [
                    'choices' => []
                ]
            ];
            /**
             * @var array $arrayTemp
            */
            $arrayTemp = [];
            foreach($dispatch as $dispatchItem){
                $arrayTemp[$dispatchItem->getId()] = $dispatchItem->getProperty(Dispatch::FIELD_DISPLAY_NAME)->getValue();
            };
            $config['elements'][BaseSubscriber::FIELD_DISPATCH]['options']['choices'] = $arrayTemp;
            unset($arrayTemp);
        }

        if(!$isAuth){
            $config['elements'][BaseSubscriber::FIELD_EMAIL] = [
                'type' => Text::TYPE_NAME,
                'label' => BaseSubscriber::FIELD_EMAIL,
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
            'type' => 'submit',
            'label' => 'Submit'
        ];

        return $this->createForm($config, $subscriber);
    }

}
