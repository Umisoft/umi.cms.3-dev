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

use umi\acl\IAclResource;
use umicms\hmvc\widget\BaseFormWidget;
use umicms\project\module\users\api\object\AuthorizedUser;
use umicms\project\module\users\api\UsersModule;

/**
 * Виджет вывода формы регистрации пользователя.
 */
class FormWidget extends BaseFormWidget implements IAclResource
{
    /**
     * @var string $template имя шаблона, по которому выводится виджет
     */
    public $template = 'form';
    /**
     * @var string $type тип регистрируемого пользователя
     */
    public $type = AuthorizedUser::TYPE_NAME;

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
    protected function getForm()
    {
        return $this->api->user()->getForm(AuthorizedUser::FORM_REGISTRATION, $this->type)
            ->setAction($this->getUrl('index'));
    }
}
 