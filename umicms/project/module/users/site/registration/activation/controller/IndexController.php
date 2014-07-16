<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\registration\activation\controller;

use umi\http\Response;
use umicms\project\module\users\model\UsersModule;
use umicms\hmvc\component\site\BaseSitePageController;

/**
 * Контроллер активации пользователя
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
     * Формирует результат работы контроллера.
     *
     * Для шаблонизации доступны следущие параметры:
     * @templateParam bool $authenticated флаг, указывающий на то, авторизован пользователь или нет
     * @templateParam umicms\project\module\structure\model\object\SystemPage $page текущая страница активации
     * @templateParam umicms\project\module\users\model\object\RegisteredUser $user активированный пользователь в случае успеха
     * @templateParam array $errors список произошедших ошибок в случае неуспеха
     *
     * @return Response
     */
    public function __invoke()
    {
        try {

            $user = $this->module->activate($this->getRouteVar('activationCode'));
            $this->commit();
            $this->module->setCurrentUser($user);

            return $this->createViewResponse(
                'index',
                [
                    'page' => $this->getCurrentPage(),
                    'authenticated' => $this->module->isAuthenticated(),
                    'user' => $user
                ]
            );

        } catch (\Exception $e) {
            return $this->createViewResponse(
                'index',
                [
                    'page' => $this->getCurrentPage(),
                    'authenticated' => $this->module->isAuthenticated(),
                    'errors' => [
                        $e->getMessage()
                    ]
                ]
            )->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

    }
}