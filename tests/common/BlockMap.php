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

    const PROFILE_FORM = '#users_profile_index';
    const PROFILE_FORM_DISPLAY_NAME = '#users_profile_index .displayName';
    const PROFILE_FORM_EMAIL = '#users_profile_index .email';
    const PROFILE_FORM_FIRST_NAME = '#users_profile_index .firstName';
    const PROFILE_FORM_MIDDLE_NAME = '#users_profile_index .middleName';
    const PROFILE_FORM_LAST_NAME = '#users_profile_index .lastName';
    const PROFILE_FORM_CSRF = '#users_profile_index .csrf';

    const PROFILE_PASSWORD_FORM = '#users_profile_password_index';
    const PROFILE_PASSWORD_FORM_PASSWORD = '#users_profile_password_index .password';
    const PROFILE_PASSWORD_FORM_NEW_PASSWORD = '#users_profile_password_index .newPassword';
    const PROFILE_PASSWORD_FORM_REDIRECT_URL = '#users_profile_password_index .redirectUrl';
    const PROFILE_PASSWORD_FORM_CSRF = '#users_profile_password_index .csrf';
    const PROFILE_PASSWORD_FORM_SUBMIT = '#users_profile_password_index #users_profile_password_index_submit';

    const LOGOUT_FORM = '#users_authorization_logoutForm';
    const VOTE_ANSWERS = '.answers label';
    const VOTE_FORM = '#surveys_voteForm';
    const BLOG_POST = '.blog-post';
    const BLOG_POST_PROGRESS_BAR = '.blog-post .progress-bar';
    const BLOG_SIDEBAR = '.blog-sidebar';
    const CAPTCHA = '.blog-post .captcha';
}
