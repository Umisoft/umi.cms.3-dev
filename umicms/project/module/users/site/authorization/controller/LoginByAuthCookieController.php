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
use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\users\model\UsersModule;

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
        $response = $this->createRedirectResponse($request->query->get('referer'));

        list($userId, $guid, $token) = $this->module->getUST($request->cookies->get(UsersModule::AUTH_COOKIE_NAME));
        $response->headers->clearCookie(UsersModule::AUTH_COOKIE_NAME);
        $userAuthCookie = $this->module->getUserAuthCookie($userId, $guid);

        if (!$userAuthCookie) {
            return $response;
        }

        if (!$this->module->isUserAuthCookieTokenValid($userAuthCookie, $token)) {
            $this->module->deleteAuthCookiesForUser($userAuthCookie->getUser());
            $this->commit();
            return $response;
        }

        if ($this->module->isUserCookieExpired($userAuthCookie, $this->getZeroDay())) {
            $this->module->deleteUserAuthCookie($userAuthCookie);
            $this->commit();
            return $response;
        }

        $this->module->generateUserAuthToken($userAuthCookie);
        $this->module->setAuthenticatedUser($userAuthCookie->getUser());
        $this->commit();

        $response->headers->setCookie(new Cookie(
            UsersModule::AUTH_COOKIE_NAME,
            $userAuthCookie->getCookieValue(),
            new \DateTime('+5 day')
        ));

        return $response;
    }

    /**
     * @return \DateTime
     */
    private function getZeroDay()
    {
        return new \DateTime('+5 day');
    }

}