<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umitest;

/**
 * Класс предоставляет карту селекторов сайта, представленных на странице.
 */
abstract class BlockMap
{
    const REGISTRATION_FORM = '#users_registration_index';
    const REGISTRATION_FORM_LOGIN_ERROR = '#users_registration_index .login';
    const REGISTRATION_FORM_PASSWORD_ERROR = '#users_registration_index .password';
    const REGISTRATION_FORM_EMAIL_ERROR = '#users_registration_index .email';

    const AUTHORIZATION_FORM = '#users_authorization_loginForm';
    const AUTHORIZATION_FORM_ERRORS = '#users_authorization_login_errors';
    const AUTHORIZATION_WELCOME = '.authorization';

    const LOGOUT_FORM = '#users_authorization_logoutForm';
    const VOTE_ANSWERS = '.answers label';
    const VOTE_FORM = '#surveys_voteForm';
    const BLOG_POST = '.blog-post';
    const BLOG_SIDEBAR = '.blog-sidebar';
    const CAPTCHA = '.captcha';
}
