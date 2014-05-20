<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */

namespace umicms\project\module\users\site\profile\widget;

use umi\authentication\exception\RuntimeException;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umi\hmvc\view\IView;
use umicms\hmvc\widget\BaseSecureWidget;
use umicms\project\module\users\api\UsersModule;

/**
 * Виджет вывода профиля текущего пользователя.
 */
class ViewWidget extends BaseSecureWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'view';

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
     * Вызывает виджет.
     * @return IView|string
     */
    public function __invoke()
    {
        try {
            $user = $this->api->getCurrentUser();
        } catch (RuntimeException $e) {
            return $this->invokeForbidden(new ResourceAccessForbiddenException($this, $e->getMessage()));
        }

        return $this->createResult(
            $this->template,
            [
                'user' => $user
            ]
        );
    }
}
 