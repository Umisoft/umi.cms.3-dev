<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model\collection;

use umi\orm\metadata\IObjectType;
use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\exception\NonexistentEntityException;
use umicms\orm\collection\CmsCollection;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\UserAuthCookie;

/**
 * @method UserAuthCookie add($typeName = IObjectType::BASE, $guid = null) Создает и возвращает auth-куку.
 */
class UserAuthCookieCollection extends CmsCollection
{

    /**
     * @param int $userId
     * @param string $authCookieGUID
     * @return UserAuthCookie
     * @throws \umicms\exception\NonexistentEntityException
     */
    public function getByUserIdAndGUID($userId, $authCookieGUID)
    {
        $userAuthCookie = $this->select()->
            begin(IFieldConditionGroup::MODE_AND)
                ->where(UserAuthCookie::FIELD_USER . '.' . RegisteredUser::FIELD_IDENTIFY)->equals((int) $userId)
                ->where(UserAuthCookie::FIELD_GUID)->equals($authCookieGUID)
            ->end()
            ->getResult()->fetch();

        if (!$userAuthCookie instanceof UserAuthCookie) {
            throw new NonexistentEntityException(
                $this->translate('Cannot find auth cookie by user_id and guid')
            );
        }

        return $userAuthCookie;
    }

}