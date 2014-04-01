<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\seo\model;

use umi\hmvc\model\IModel;

/**
 * Модель, получающая данные от API Мегаиндекса.
 */
class MegaindexModel implements IModel
{
    /**
     * Логин в систему Мегаиндекс
     * @var string $login
     */
    protected $login;
    /**
     * Пароль к логину
     * @var string $password
     */
    protected $password;
    /**
     * Ссылка на сайт проекта
     * @var string $password
     */
    protected $siteUrl;

    /**
     * Конструктор модели, требует логин и пароль к системе Мегаиндекс.
     * @param string $login
     * @param string $password
     * @param $siteUrl
     */
    public function __construct($login, $password, $siteUrl)
    {
        $this->login = $login;
        $this->password = $password;
        $this->siteUrl = $siteUrl;
    }

    /**
     * @param string $method
     * @param array $params
     * @return array
     */
    public function queryApi($method, $params)
    {
        $paramsMerged = array_merge(
            [
                'login' => $this->login,
                'password' => $this->password,
                'url' => $this->siteUrl,
            ],
            $params
        );
        $paramsMerged['method'] = $method;
        return \GuzzleHttp\get('http://api.megaindex.ru/', ['query' => $paramsMerged])->json();
    }
}
