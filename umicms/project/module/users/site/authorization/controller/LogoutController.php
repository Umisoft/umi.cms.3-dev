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

use umicms\hmvc\controller\BaseController;
use umicms\project\module\users\api\object\AuthorizedUser;
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

        $form = $this->api->user()->getForm(AuthorizedUser::FORM_LOGOUT_SITE, AuthorizedUser::TYPE_NAME);

        if ($this->isRequestMethodPost()) {
            $form->setData($this->getAllPostVars());
            if ($form->isValid()) {
                $this->api->logout();
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
 