<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\restoration\confirmation\controller;

use umi\http\Response;
use umicms\project\module\users\model\object\AuthorizedUser;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;

/**
 * Контроллер сброса пароля пользователя
 */
class IndexController extends BaseSitePageController
{
    /**
     * @var UsersModule $module модуль "Пользователи"
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
     * {@inheritdoc}
     */
    public function __invoke()
    {
        try {

            $user = $this->module->changePassword($this->getRouteVar('activationCode'));
            $this->commit();

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
            $this->module->getMailSender(),
            'mail/newPasswordSubject',
            'mail/newPasswordBody',
            [
                'password' => $user->getPassword(),
                'user' => $user
            ]
        );
    }
}