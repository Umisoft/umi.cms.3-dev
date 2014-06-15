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

use umicms\hmvc\controller\BaseCmsController;
use umicms\project\module\users\model\object\AuthorizedUser;
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

        $form = $this->module->user()->getForm(AuthorizedUser::FORM_LOGOUT_SITE, AuthorizedUser::TYPE_NAME);

        if ($this->isRequestMethodPost()) {
            $form->setData($this->getAllPostVars());
            if ($form->isValid()) {
                $this->module->logout();
            }
        }

        $referer = $this->getRequest()->getReferer();
        if ($referer && strpos($referer, $this->getUrlManager()->getProjectUrl(true)) === 0) {
            return $this->createRedirectResponse($referer);
        }

        return $this->createRedirectResponse(
            $this->getUrl('login', [], true)
        );
    }
}
 