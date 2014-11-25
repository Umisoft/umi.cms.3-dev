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

use umicms\hmvc\component\BaseCmsController;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Крнтроллер "разавторизации" пользователя.
 */
class LogoutController extends BaseCmsController
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

        $form = $this->module->user()->getForm(RegisteredUser::FORM_LOGOUT_SITE, RegisteredUser::TYPE_NAME);

        if ($this->isRequestMethodPost()) {
            $form->setData($this->getAllPostVars());
            if ($form->isValid()) {
                $this->module->logout();
            }
        }

        $referer = $this->getReferer();

        if ($referer === $this->getCurrentUrl()) {
            $referer = null;
        }

        if ($referer && strpos($referer, $this->getProjectUrl()) === 0) {
            return $this->createRedirectResponse($referer);
        }

        return $this->createRedirectResponse(
            $this->getUrl('login', [], true)
        );
    }

    /**
     * Получает значение заголовка "referer"
     * @return null|string
     */
    private function getReferer()
    {
        return $this->getRequest()->getReferer();
    }

    /**
     * Получает текущий URL
     * @return string
     */
    private function getCurrentUrl()
    {
        return $this->getUrlManager()->getCurrentUrl(true);
    }

    /**
     * Получает URL проекта
     * @return string
     */
    private function getProjectUrl()
    {
        return $this->getUrlManager()->getProjectUrl(true);
    }
}
 