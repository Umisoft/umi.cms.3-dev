<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\authentication;

use umi\authentication\adapter\ORMAdapter;
use umi\authentication\result\AuthResult;
use umi\authentication\result\IAuthResult;
use umi\orm\object\IObject;
use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\project\module\users\model\object\AuthorizedUser;

/**
 * {@inheritdoc}
 */
class CmsUserAdapter extends ORMAdapter
{
    /**
     * Проверяет валидность пароля для указанного пользователя.
     * @param IObject $user пользователь
     * @param string $password пароль
     * @return bool
     */
    public function checkPassword(IObject $user, $password) {
        $passwordHash = crypt($password, $user->getProperty(AuthorizedUser::FIELD_PASSWORD_SALT)->getValue());

        return $user->getProperty($this->passwordField)->getValue() === $passwordHash;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate($username, $password)
    {
        $usersSelector = $this->getCollectionManager()
            ->getCollection($this->collectionName)
            ->select();

        $usersSelector->begin(IFieldConditionGroup::MODE_OR);
        foreach ($this->usernameFields as $fieldName) {
            $usersSelector->where($fieldName)
                ->equals($username);
        }
        $usersSelector->end();
        $usersSelector->limit(1);
        $usersSelector->localization();

        $user = $usersSelector->result()
            ->fetch();

        if (!$user instanceof AuthorizedUser) {
            return new AuthResult(IAuthResult::WRONG_USERNAME);
        } elseif (!$this->checkPassword($user, $password)) {
            return new AuthResult(IAuthResult::WRONG_PASSWORD);
        } else {
            return new AuthResult(IAuthResult::SUCCESSFUL, $user->getId());
        }
    }
}
 