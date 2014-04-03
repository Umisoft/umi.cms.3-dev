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
     * Логин в системе Мегаиндекс
     * @var string $login
     */
    protected $login;
    /**
     * Пароль в системе Мегаиндекс
     * @var string $password
     */
    protected $password;
    /**
     * Ссылка на сайт (домен) проекта
     * @var string $siteUrl
     */
    protected $siteUrl;

    /**
     * Конструктор модели, получает необходимые настройки для работы с Мегаиндексом.
     * @param string $login
     * @param string $password
     * @param string $siteUrl
     */
    public function __construct($login, $password, $siteUrl)
    {
        $this->login = $login;
        $this->password = $password;
        $this->siteUrl = $siteUrl;
    }

    /**
     * Универсальный метод, запрашивающий данные через API Мегаиндекса.
     * Возвращает массив данных
     * @param string $method �?мя метода, методы перечислены в {@link http://api.megaindex.ru/description/ документации}
     * @param array $params Дополнительные параметры запроса
     * @return array
     */
    public function queryApi($method, $params = [])
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
        return \GuzzleHttp\get('http://api.megaindex.ru/', ['query' => $paramsMerged])
            ->json(['object' => false]);
    }
}
