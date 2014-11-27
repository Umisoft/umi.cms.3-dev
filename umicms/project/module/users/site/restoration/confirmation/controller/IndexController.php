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
use umicms\project\module\users\model\object\RegisteredUser;
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
     * @var bool $success успех операции сброса пароля
     */
    private $success = false;

    /**
     * Конструктор.
     * @param UsersModule $module модуль "Пользователи"
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница сброса пароля пользователя
     * @templateParam bool $success флаг, указывающий на успешное отправку нового пароля
     * @templateParam array $errors список произошедших ошибок, не передается, если ошибок не было
     *
     * @return Response
     */
    public function __invoke()
    {
        try {

            $user = $this->module->changePassword($this->getRouteVar('activationCode'));
            $this->commit();

            $this->sendNewPassword($user);

            $this->success = true;

            return $this->createRedirectResponse($this->getUrl('success'));

        } catch (\Exception $e) {
            return $this->createViewResponse(
                $this->template,
                [
                    'page' => $this->getCurrentPage(),
                    'success' => $this->success,
                    'errors' => [
                        $e->getMessage()
                    ]
                ]
            )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Отпраляет пользователю письмо с новым паролем
     * @param RegisteredUser $user
     */
    protected function sendNewPassword(RegisteredUser $user)
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