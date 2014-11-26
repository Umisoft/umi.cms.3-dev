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
 * Класс предоставляет карту маршрутов сайта, представленных на странице.
 */
abstract class UrlMap
{
    const DEFAULT_URL = '';
    const USER_AUTHORIZATION = '/users/auth';
    const USER_REGISTRATION = '/users/registration';
    const USER_ACTIVATION = '/users/registration/activate';
    const USER_LOGOUT = '/users/auth/logout';
    const USER_EDIT_PROFILE = '/users/profile';
    const USER_RESTORE = '/users/restore';
    const SURVEYS_NEXT_SHOW = '/surveys/nextshow';

    public static $defaultUrl;
    public static $userAuthorization;
    public static $userActivation;
    public static $userRegistration;
    public static $userLogout;
    public static $userEditProfile;
    public static $userRestore;

    private static $projectDomain;

    /**
     * @return mixed
     */
    public static function getProjectDomain()
    {
        return self::$projectDomain;
    }

    /**
     * @param mixed $projectDomain
     */
    public static function setProjectDomain($projectDomain)
    {
        self::$projectDomain = $projectDomain;
    }


}
