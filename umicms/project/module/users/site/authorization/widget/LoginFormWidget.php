<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\authorization\widget;

use umi\form\element\IFormElement;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;

/**
 * Виджет вывода формы авторизации.
 */
class LoginFormWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'loginForm';

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
        $form = $this->api->user()->getForm(AuthorizedUser::FORM_LOGIN_SITE, 'authorized');
        $form->setAction($this->getUrl('login'));

        /**
         * @var IFormElement $refererInput
         */
        $refererInput = $form->get('referer');
        $refererInput->setValue($this->getUrlManager()->getCurrentUrl(true));

        return $this->createResult(
            $this->template,
            [
                'form' => $form
            ]
        );
    }
}
 