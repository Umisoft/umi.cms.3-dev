<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\authorization\controller;

use Symfony\Component\HttpFoundation\Cookie;
use umi\http\Response;
use umicms\exception\UnexpectedValueException;
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Контроллер аутентификации по куке
 */
class LoginByAuthCookieController extends BaseCmsController
{

    /**
     * @var UsersModule
     */
    protected $module;

    /**
     * Конструктор.
     * @param UsersModule $module модуль "Пользователи"
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * Вызывает контроллер.
     * @return Response
     */
    public function __invoke()
    {
        $request = $this->getRequest();
        $referer = $request->query->get('referer') ? : $this->getUrlManager()->getProjectUrl();
        $response = $this->createRedirectResponse($referer);

        if ($request->cookies->has(UsersModule::AUTH_COOKIE_NAME)) {
            $userAuthCookie = $request->cookies->get(UsersModule::AUTH_COOKIE_NAME);
            $response->headers->clearCookie(UsersModule::AUTH_COOKIE_NAME);
        } else {
            return $response;
        }

        try {
            list($userId, $guid, $token) = $this->module->getUST($userAuthCookie);
        } catch (UnexpectedValueException $e) {
            return $response;
        }

        $userAuthCookie = $this->module->getUserAuthCookie($userId, $guid);

        if (!$userAuthCookie) {
            return $response;
        }

        $user = $userAuthCookie->getUser();

        if (!$this->module->isUserAuthCookieTokenValid($userAuthCookie, $token)) {
            $this->module->deleteAuthCookiesForUser($user);
            $this->commit();
            $this->sendWarningNotification($user);
            return $response;
        }

        if ($this->module->isUserCookieExpired($userAuthCookie)) {
            $this->module->deleteUserAuthCookie($userAuthCookie);
            $this->commit();
            return $response;
        }

        $this->module->generateUserAuthToken($userAuthCookie);
        $this->module->setAuthenticatedUser($user);
        $this->commit();

        $response->headers->setCookie(new Cookie(
            UsersModule::AUTH_COOKIE_NAME,
            $userAuthCookie->getCookieValue(),
            $this->module->getAuthCookieTTLFromSettings()
        ));

        return $response;
    }

    /**
     * Отправляет пользователю письмо-оповещение о возможном взломе
     * @param RegisteredUser $user
     */
    private function sendWarningNotification(RegisteredUser $user)
    {
        $this->mail(
            [$user->email => $user->displayName],
            $this->module->getMailSender(),
            'mail/warningNotificationSubject',
            'mail/warningNotificationBody',
            []
        );
    }

}