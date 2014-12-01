<?php
namespace umitest\FunctionalTester;

use umitest\BlockMap;
use umitest\FunctionalTester;
use umitest\UrlMap;

class CommonSteps extends FunctionalTester
{
    /**
     * Авторизация пользователя
     * @param string $login
     * @param string $password
     */
    public function login($login, $password)
    {
        $I = $this;
        $I->amOnPage(UrlMap::$projectUrl);
        $I->submitForm(
            BlockMap::AUTHORIZATION_FORM,
            [
                'login'    => $login,
                'password' => $password
            ]
        );
    }
}