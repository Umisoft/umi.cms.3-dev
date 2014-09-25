<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\site\registration\widget;

use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Виджет вывода формы регистрации пользователя.
 */
class FormWidget extends BaseFormWidget
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'form';
    /**
     * @var string $type тип регистрируемого пользователя
     */
    public $type = RegisteredUser::TYPE_NAME;

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
    protected function getForm()
    {
        $user = $this->module->user()->add($this->type);

        return $this->module->user()->getForm(RegisteredUser::FORM_REGISTRATION, $this->type, $user)
            ->setAction($this->getUrl('index', ['type' => $this->type]));
    }
}
 