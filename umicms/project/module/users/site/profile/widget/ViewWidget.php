<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\profile\widget;

use umi\authentication\exception\RuntimeException;
use umi\hmvc\exception\acl\ResourceAccessForbiddenException;
use umicms\hmvc\view\CmsView;
use umicms\hmvc\widget\BaseCmsWidget;
use umicms\project\module\users\model\UsersModule;

/**
 * Виджет вывода профиля текущего пользователя.
 */
class ViewWidget extends BaseCmsWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'view';

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
     * Формирует результат работы виджета.
     * Для шаблонизации доступны следущие параметры:
     *
     * @templateParam umicms\project\module\users\model\object\RegisteredUser $user текущий пользователь
     *
     * @throws ResourceAccessForbiddenException
     * @return CmsView
     */
    public function __invoke()
    {
        try {
            $user = $this->module->getAuthenticatedUser();
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
 