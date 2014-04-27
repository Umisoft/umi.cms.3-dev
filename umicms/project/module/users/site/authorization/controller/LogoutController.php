<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\authorization\controller;

use umicms\hmvc\controller\BaseController;
use umicms\project\module\users\api\UsersModule;

/**
 * Крнтроллер "разавторизации" пользователя.
 */
class LogoutController extends BaseController
{
    /**
     * @var UsersModule $api API модуля "Пользователи"
     */
    protected $api;

    /**
     * Конструктор.
     * @param UsersModule $usersModule API модуля "Пользователи"
     */
    public function __construct(UsersModule $usersModule)
    {
        $this->api = $usersModule;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $this->api->logout();

        $referer = $this->getRequest()->getReferer();
        if ($referer && strpos($referer, $this->getUrlManager()->getProjectUrl(true)) === 0) {
            return $this->createRedirectResponse($referer);
        }

        return $this->createRedirectResponse(
            $this->getUrl('login', [], true)
        );
    }
}
 