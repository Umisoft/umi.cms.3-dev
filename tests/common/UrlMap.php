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
    /**
     * @var string $projectUrl url проекта
     */
    public static $projectUrl = '';
    /**
     * @var string $projectUrl абсолютный url проекта
     */
    public static $projectAbsoluteUrl = '';


    public static $defaultPageUrl = '/main';
    public static $userAuthorization = '/users/auth';
    public static $userActivation =  '/users/registration/activate';
    public static $userRegistration = '/users/registration';
    public static $userLogout = '/users/auth/logout';
    public static $userEditProfile = '/users/profile';
    public static $userRestore =  '/users/restore';
    public static $userRestoreConfirm = '/users/restore/confirm';
    public static $userProfile ='/users/profile';
    public static $userProfilePass ='/users/profile/pass';

    public static $surveysNextShow = '/surveys/nextshow';

}
