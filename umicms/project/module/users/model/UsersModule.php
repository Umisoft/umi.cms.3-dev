<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\model;

use umi\http\IHttpAware;
use umi\http\THttpAware;
use umi\session\ISessionAware;
use umi\session\TSessionAware;
use umicms\exception\NonexistentEntityException;
use umicms\exception\RuntimeException;
use umicms\module\BaseModule;
use umicms\project\Bootstrap;
use umicms\project\module\users\model\collection\UserCollection;
use umicms\project\module\users\model\collection\UserGroupCollection;
use umicms\project\module\users\model\object\BaseUser;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\object\Guest;
use umicms\project\module\users\model\object\Supervisor;
use umicms\project\module\users\model\object\UserGroup;
use umicms\project\module\users\model\object\Visitor;
use umicms\Utils;

/**
 * Модуль для работы с пользователями.
 */
class UsersModule extends BaseModule implements IHttpAware, ISessionAware
{
    use THttpAware;
    use TSessionAware;

    /**
     * Имя куки, для токена посетителя.
     */
    const VISITOR_TOKEN_COOKIE_NAME = 'visitorToken';
    /**
     * Название аттрибута в сессии для хранения идентификатора авторизованного пользователя
     */
    const IDENTITY_ATTRIBUTE_NAME = 'identity';
    /**
     * Имя контейнера сессии.
     */
    const SESSION_NAMESPACE = 'authentication';

    /**
     * Настройка отправителя писем
     */
    const SETTING_MAIL_SENDER = 'mailFromEmail';
    /**
     * Настройка получателей уведомлений
     */
    const SETTING_MAIL_NOTIFICATION_RECIPIENTS = 'registeredUserNotificationEmails';

    /**
     * @var string $guestGuid GUID гостя
     */
    public $guestGuid = '552802d2-278c-46c2-9525-cd464bbed63e';
    /**
     * @var string $supervisorGuid GUID супервайзера
     */
    public $supervisorGuid = '68347a1d-c6ea-49c0-9ec3-b7406e42b01e';

    /**
     * @var Visitor $visitor посетитель
     */
    private $visitor;

    /**
     * Возвращает репозиторий для работы с пользователями.
     * @return UserCollection
     */
    public function user()
    {
        return $this->getCollection('user');
    }

    /**
     * Возвращает репозиторий для работы с группами пользователей.
     * @return UserGroupCollection
     */
    public function userGroup()
    {
        return $this->getCollection('userGroup');
    }

    /**
     * Производит попытку авторизации в системе.
     * @param string $login логин пользователя
     * @param string $password пароль
     * @return bool результат авторизации
     */
    public function login($login, $password)
    {
        if ($this->isAuthenticated()) {
            return false;
        }

        try {
            $user = $this->user()->getUserByLoginOrEmail($login);
        } catch (NonexistentEntityException $e) {
            return false;
        }

        $success = $user->checkPassword($password);

        if ($success) {
            $this->setAuthenticatedUser($user);
        }

        return $success;
    }

    /**
     * Регистрирует пользователя в системе.
     * @param RegisteredUser $user
     * @return RegisteredUser
     */
    public function register(RegisteredUser $user)
    {
        if ($this->user()->getIsRegistrationWithActivation()) {
            $this->user()->deactivate($user);
        } else {
            $this->user()->activate($user);
        }

        $user->updateActivationCode();

        $userGroups = $user->groups;

        $defaultGroups = $this->userGroup()
            ->select()
            ->fields([UserGroup::FIELD_GUID])
            ->where(UserGroup::FIELD_GUID)
                ->in($this->user()->getRegisteredUsersDefaultGroupGuids());

        foreach ($defaultGroups as $group)
        {
            $userGroups->link($group);
        }

        $user->registrationDate = new \DateTime();
        $user->getProperty(RegisteredUser::FIELD_IP)->setValue($this->getHttpRequest()->server->get('REMOTE_ADDR'));

        return $user;
    }

    /**
     * Активирует неактивированного пользователя по ключу авторизации.
     * @param string $activationCode
     * @return RegisteredUser
     */
    public function activate($activationCode)
    {
        $user = $this->user()->getUserByActivationCode($activationCode);
        $user->updateActivationCode();
        $this->user()->activate($user);

        return $user;
    }

    /**
     * Выставляет пользователю новый пароль по ключу активации.
     * @param string $activationCode
     * @return RegisteredUser
     */
    public function changePassword($activationCode)
    {
        return
            $this->user()->getUserByActivationCode($activationCode, true)
                ->setPassword($this->getRandomPassword())
                ->updateActivationCode();
    }

    /**
     * Генерирует псевдо случайный пароль.
     * @param int $length длина
     * @return string
     */
    public function getRandomPassword($length = 12)
    {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $password = base64_encode(openssl_random_pseudo_bytes($length, $strong));
            if ($strong) {

                return substr($password, 0, $length);
            }
        }

        $letters = "$#@^&!1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $size = strlen($letters);

        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $c = rand(0, $size - 1);
            $password .= $letters[$c];
        }

        return $password;
    }

    /**
     * Возвращает авторизованного пользователя.
     * @param bool $forceVisitorCreation необходимость создавать посетителя, если текущий пользователь не определен
     * @return BaseUser|RegisteredUser|Visitor|Guest
     */
    public function getCurrentUser($forceVisitorCreation = false)
    {
        if ($this->isAuthenticated()) {
            return $this->getAuthenticatedUser();
        }

        if ($this->isVisitor()) {
            return $this->getVisitor();
        }

        if ($forceVisitorCreation) {
            return $this->createVisitor();
        }

        return $this->getGuest();

    }

    /**
     * Устанавливает авторизованного пользователя.
     * @param RegisteredUser $user
     * @return $this
     */
    public function setAuthenticatedUser(RegisteredUser $user)
    {
        $this->setSessionVar(self::IDENTITY_ATTRIBUTE_NAME, $user->getId());

        return $this;
    }

    /**
     * Возвращает текущего посетителя.
     * @throws RuntimeException
     * @return Visitor
     */
    public function getVisitor()
    {
        if ($this->visitor) {
            return $this->visitor;
        }

        $visitorToken = $this->getHttpRequest()->cookies->get(self::VISITOR_TOKEN_COOKIE_NAME);

        if (is_null($visitorToken)) {
            throw new RuntimeException(
                'Authentication token does not exist.'
            );
        }

        return $this->user()->getVisitorByToken($visitorToken);

    }

    /**
     * Устанавливает текущего посетителя.
     * @param Visitor $visitor
     * @return $this
     */
    public function setVisitor(Visitor $visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * Проверяет, является ли пользователь посетителем
     * @return bool
     */
    public function isVisitor()
    {
        if ($this->visitor) {
            return true;
        }

        if ($this->getHttpRequest()->cookies->has(self::VISITOR_TOKEN_COOKIE_NAME)) {

            try {
                $this->getVisitor();

                return true;
            } catch (\Exception $e) {}
        }

        return false;
    }

    /**
     * Возвращает текущего авторизованного пользователя.
     * @throws RuntimeException если пользователь не авторизован
     * @return RegisteredUser
     */
    public function getAuthenticatedUser()
    {
        $userId = $this->getSessionVar(self::IDENTITY_ATTRIBUTE_NAME);

        if (is_null($userId)) {
            throw new RuntimeException(
                'Authentication identity does not exist.'
            );
        }

        return $this->user()->getById($userId);
    }

    /**
     * Проверяет, авторизован ли пользователь в системе.
     * @return bool
     */
    public function isAuthenticated()
    {
        if (!$this->getHttpRequest()->cookies->has(Bootstrap::SESSION_COOKIE_NAME)) {
            return false;
        }

        if ($this->hasSessionVar(self::IDENTITY_ATTRIBUTE_NAME)) {
            try {
                $this->getAuthenticatedUser();

                return true;
            } catch (\Exception $e) {
                $this->logout();
            }
        }

        return false;
    }

    /**
     * Уничтожает данные текущей авторизации.
     * @return $this
     */
    public function logout()
    {
        $this->removeSessionVar(self::IDENTITY_ATTRIBUTE_NAME);

        return $this;
    }

    /**
     * Возвращает гостя.
     * @return Guest
     */
    public function getGuest()
    {
        return $this->user()->get($this->guestGuid);
    }

    /**
     * Возвращает супервайзера.
     * @return Supervisor
     */
    public function getSupervisor()
    {
        return $this->user()->get($this->supervisorGuid);
    }

    /**
     * Возвращает отправителя электронных писем.
     * @return array|null
     */
    public function getMailSender()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_SENDER));
    }

    /**
     * Возвращает получателей уведомлений.
     * @return array|null
     */
    public function getNotificationRecipients()
    {
        return Utils::parseEmailList($this->getSetting(self::SETTING_MAIL_NOTIFICATION_RECIPIENTS));
    }

    /**
     * Создает нового посетителя или отдает текущего.
     * @return Visitor
     */
    protected function createVisitor()
    {
        if ($this->visitor) {
            return $this->visitor;
        }

        /**
         * @var Visitor $visitor
         */
        $visitor = $this->user()->add(Visitor::TYPE_NAME);
        $visitor->getProperty(Visitor::FIELD_IP)->setValue($this->getHttpRequest()->server->get('REMOTE_ADDR'));
        $visitor->updateToken();

        $this->setVisitor($visitor);

        return $visitor;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSessionNamespacePath()
    {
        return self::SESSION_NAMESPACE;
    }

}
