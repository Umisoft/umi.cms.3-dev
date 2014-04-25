<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\authorization\controller;

use umi\form\element\IFormElement;
use umi\http\Response;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;
use umicms\project\site\controller\SitePageController;

/**
 * Контроллер авторизации пользователя
 */
class LoginController extends SitePageController
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

        $form = $this->api->user()->getForm('login', 'authorized');
        $formValid = true;

        if ($this->isRequestMethodPost()) {

            $form->setData($this->getAllPostVars());
            $formValid = $form->isValid();


            if ($formValid) {

                if ($this->api->isAuthenticated()) {
                    $this->api->logout();
                }

                /**
                 * @var IFormElement $loginInput
                 */
                $loginInput = $form->get(AuthorizedUser::FIELD_LOGIN);
                /**
                 * @var IFormElement $passwordInput
                 */
                $passwordInput = $form->get(AuthorizedUser::FIELD_PASSWORD);

                if ($this->api->login($loginInput->getValue(), $passwordInput->getValue())) {
                    /**
                     * @var IFormElement $refererInput
                     */
                    $refererInput = $form->get('referer');

/*                    var_dump(
                        $this->getUrlManager()->getProjectDomainUrl(),
                        $this->getUrlManager()->getProjectUrl(true)
                    );
                    exit();*/

                    $referer = $refererInput->getValue() ? $refererInput->getValue() : $this->getRequest()->getReferer();
                    if ($referer && strpos($referer, $this->getUrlManager()->getProjectUrl(true)) === 0) {
                        return $this->createRedirectResponse($referer);
                    }
                } else {
                    $error = $this->translate('Invalid login or password');
                }
            }
        }

        $result = [
            'page' => $this->getCurrentPage(),
            'form' => $form,
            'authenticated' => $this->api->isAuthenticated()
        ];

        if (isset($error)) {
            $result['error'] = $error;
        }

        $response = $this->createViewResponse(
            'index',
            $result
        );

        if (!$formValid) {
            $response->getStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}