<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\restoration\confirmation\controller;

use umi\http\Response;
use umi\orm\persister\IObjectPersisterAware;
use umi\orm\persister\TObjectPersisterAware;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\site\controller\SitePageController;

/**
 * Контроллер сброса пароля пользователя
 */
class IndexController extends SitePageController implements IObjectPersisterAware
{
    use TObjectPersisterAware;

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
        try {

            $user = $this->api->changePassword($this->getRouteVar('activationCode'));
            $this->getObjectPersister()->commit();

            $this->sendNewPassword($user);

            return $this->createViewResponse(
                'index',
                [
                    'page' => $this->getCurrentPage()
                ]
            );

        } catch (\Exception $e) {
            return $this->createViewResponse(
                'index',
                [
                    'page' => $this->getCurrentPage(),
                    'errors' => [
                        $e->getMessage()
                    ]
                ]
            )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Отпраляет пользователю письмо с новым паролем
     */
    protected function sendNewPassword(AuthorizedUser $user)
    {
        $this->mail(
            [$user->email => $user->displayName],
            $this->api->user()->getMailSender(),
            'mail/newPasswordSubject',
            'mail/newPasswordBody',
            [
                'password' => $user->getPassword(),
                'user' => $user
            ]
        );
    }
}