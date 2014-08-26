<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\users\admin\user\controller;

use umi\hmvc\exception\http\HttpException;
use umi\http\Response;
use umicms\hmvc\component\admin\collection\ActionController as CollectionActionController;
use umicms\project\module\users\model\collection\UserCollection;
use umicms\project\module\users\model\object\RegisteredUser;
use umicms\project\module\users\model\UsersModule;

/**
 * Контроллер операций.
 */
class ActionController extends CollectionActionController
{
    /**
     * @var UsersModule $module модуль "Пользователи"
     */
    protected $module;

    /**
     * Конструктор.
     * @param UsersModule $module
     */
    public function __construct(UsersModule $module)
    {
        $this->module = $module;
    }

    /**
     * Возвращает форму для смены пароля пользователя.
     */
    protected function actionGetChangePasswordForm()
    {
        $form = $this->getCollection()->getForm(UserCollection::FORM_CHANGE_PASSWORD, RegisteredUser::TYPE_NAME);
        $form->setAction($this->getUrlManager()->getAdminComponentActionResourceUrl(
                $this->getComponent(), UserCollection::ACTION_CHANGE_PASSWORD)
        );

        return $form->getView();
    }

    /**
     * Изменяет пароль пользователя.
     * @throws \umi\hmvc\exception\http\HttpException
     * @return RegisteredUser
     */
    protected function actionChangePassword()
    {
        $data = $this->getIncomingData();
        $object = $this->getEditedObject($data);

        if (!isset($data[RegisteredUser::FIELD_PASSWORD])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. Password is required.')
            );
        }

        if (!isset($data['newPassword'])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. Password confirm is required.')
            );
        }

        if ($data[RegisteredUser::FIELD_PASSWORD] !== $data['newPassword']) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. Password is not unique.')
            );
        }

        if ($object instanceof RegisteredUser) {
            $object->setPassword($data[RegisteredUser::FIELD_PASSWORD]);
        }

        $this->commit();

        return $object;
    }
}
 