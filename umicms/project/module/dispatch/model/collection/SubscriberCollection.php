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

/**
 * Коллекция для работы с подписчиками.
 *
 */
class SubscriberCollection extends CmsCollection
{

    /**
     * Проверяет уникальность e-mail пользователя.
     * @param $email
     * @return bool
     */
    public function checkEmailUniqueness($email)
    {
        $subscribers = $this->getInternalSelector()
            ->fields([BaseSubscriber::FIELD_IDENTIFY])
            ->where(BaseSubscriber::FIELD_EMAIL)
            ->equals($email)
            /*->where(BaseSubscriber::FIELD_IDENTIFY)
            ->notEquals($user->getId())*/
            ->getResult();

        return !count($subscribers);
    }

}
