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
        $form = $this->getCollection()->getForm(UserCollection::FORM_CHANGE_PASSWORD_ADMIN, RegisteredUser::TYPE_NAME);
        $form->setAction($this->getUrlManager()->getAdminComponentActionResourceUrl(
                $this->getComponent(), UserCollection::ACTION_CHANGE_PASSWORD)
        );

        return $form->getView();
    }

    /**
     * Изменяет пароль пользователя.
     * @throws HttpException
     * @return RegisteredUser
     */
    protected function actionChangePassword()
    {
        $data = $this->getIncomingData();
        $user = $this->getEditedObject($data);

        if (!$user instanceof RegisteredUser) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate(
                    'Cannot change password.' .
                    'Object should be instance of "umicms\project\module\users\model\object\RegisteredUser".' .
                    'Object of class "{class}" given.',
                    ['class' => get_class($user)]
                )
            );
        }

        $currentPassword = isset($data[RegisteredUser::FIELD_PASSWORD]) ? trim($data[RegisteredUser::FIELD_PASSWORD]) : '';

        if ($currentPassword === '') {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. Current password is required.')
            );
        }

        if (!$user->checkPassword($data[RegisteredUser::FIELD_PASSWORD])) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Current password is not valid.')
            );
        }

        $newPassword = isset($data['newPassword'][0]) ? trim($data['newPassword'][0]) : '';

        if ($newPassword === '') {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. New password is required.')
            );
        }

        $newPasswordConfirmation = isset($data['newPassword'][1]) ? trim($data['newPassword'][1]) : '';

        if ($newPasswordConfirmation === '') {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. New password confirmation is required.')
            );
        }

        if ($newPassword !== $newPasswordConfirmation) {
            throw new HttpException(
                Response::HTTP_BAD_REQUEST,
                $this->translate('Cannot change password. Passwords are not equal.')
            );
        }

        $user->setPassword($newPassword);

        $this->commit();

        return $user;
    }
}
 