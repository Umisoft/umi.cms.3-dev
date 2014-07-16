<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\context\admin\page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;
use tests\context\BaseCmsPageObject;

/**
 * Страница авторизации в административной панели
 */
class AuthFormPage extends BaseCmsPageObject
{
    /**
     * @var string $path путь страницы
     */
    protected $path = '/admin';

    /**
     * @var array $elements элементы страницы
     */
    protected $elements = [
        'authForm' =>  ['xpath' => '//form[@name="auth"]'],
        'adminLayout' => ['css' => 'body.ember-application'],
        'serverError' => ['css' => 'div.errors-list div']
    ];

    /**
     * Открывает страницу авторизации
     * @param array $urlParameters
     * @return $this
     */
    public function open(array $urlParameters = array()) {
        if (!$this->isCurrent($urlParameters)) {
            parent::open($urlParameters);
            $this->waitForElements('authForm');
        }
        $this->resetAuthForm();

        return $this;
    }

    /**
     * Заполнить логин
     * @param string $login
     * @return $this
     */
    public function fillLogin($login)
    {
        $this->fillField('login', $login);

        return $this;
    }

    /**
     * Заполнить пароль
     * @param string $password
     * @return $this
     */
    public function fillPassword($password)
    {
        $this->fillField('password', $password);

        return $this;
    }

    /**
     * Проверяет, успешно ли авторизован пользователь в административной панели
     * после отправки формы авторизации
     * @return bool
     */
    public function isAuthenticated()
    {
        try {
            $this->waitForElements('adminLayout');
        } catch (ElementNotFoundException $e) {
            return false;
        }

        return true;
    }

    /**
     * Проверяет наличие ошибки
     * @param string $message
     * @return bool
     */
    public function checkForServerError($message)
    {
        return $this->hasText($this->waitForElements('serverError'), $message);
    }

    /**
     * Отправляет форму авторизации.
     * @return $this
     */
    public function submitAuthForm()
    {
        $this->pressButton('submit');
        $this->waitForAnyElement(['serverError', 'adminLayout']);

        return $this;
    }

    /**
     * Сбрасывает форму.
     * @return $this
     */
    public function resetAuthForm()
    {
        $this->getSession()->executeScript('document.forms.auth.reset()');
        if ($link = $this->find('css', 'a.close')) {
            $link->click();
        }

        return $this;
    }

}
 