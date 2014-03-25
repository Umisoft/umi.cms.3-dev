<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\authentication;

use umi\authentication\adapter\ORMAdapter;
use umi\authentication\result\AuthResult;
use umi\authentication\result\IAuthResult;
use umi\orm\object\IObject;
use umi\orm\selector\condition\IFieldConditionGroup;
use umicms\project\module\users\api\object\AuthorizedUser;

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
        $usersSelector->withLocalization();

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
 