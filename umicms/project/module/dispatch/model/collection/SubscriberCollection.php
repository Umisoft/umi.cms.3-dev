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

use umi\i18n\ILocalesService;
use umi\orm\metadata\IObjectType;
use umi\orm\object\IHierarchicObject;
use umicms\orm\collection\CmsCollection;
use umicms\orm\selector\CmsSelector;
use umicms\project\module\dispatch\model\object\Subscriber;
use umicms\project\module\dispatch\model\object\BaseSubscriber;
use umicms\exception\NonexistentEntityException;
use umi\filter\IFilterFactory;
use umi\validation\IValidatorFactory;

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
     * @param Subscriber $subscriber логин пользователя
     * @param bool $isAuth
     * @return \umi\form\IForm
     */
    public function getSubscribeForm(Subscriber $subscriber, $isAuth = false)
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

        if(!$isAuth){
            $config['elements'][BaseSubscriber::FIELD_EMAIL] = [
                'type' => 'text',
                'label' => BaseSubscriber::FIELD_EMAIL,
                'options' => [
                    'filters' => [
                        IFilterFactory::TYPE_STRING_TRIM => []
                    ],
                    'validators' => [
                        IValidatorFactory::TYPE_REQUIRED => [],
                        IValidatorFactory::TYPE_EMAIL => []
                    ]
                ]
            ];
        }

        $config['elements']['submit'] = [
            'type' => 'submit',
            'label' => 'Submit'
        ];

        return $this->createForm($config, $subscriber);
    }

}
