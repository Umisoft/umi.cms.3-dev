<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace umicms\project\module\seo\model;

use umi\hmvc\model\IModel;
use umicms\exception\InvalidArgumentException;
use umicms\hmvc\component\admin\AdminComponent;

/**
 * Модель, получающая данные от API Мегаиндекса.
 */
class MegaindexModel implements IModel
{
    /**
     * Логин в системе MegaIndex.
     */
    const MEGAINDEX_LOGIN = 'login';
    /**
     * Пароль в системе MegaIndex.
     */
    const MEGAINDEX_PASSWORD = 'password';
    /**
     * Адрес анализируемого сайта в системе MegaIndex.
     */
    const MEGAINDEX_SITE_URL = 'siteUrl';

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
     * Админский компонент
     * @var AdminComponent $component
     */
    private $component;
    /**
     * @var string $translationPrefix префикс переводимых меток
     */
    private $translationPrefix = 'component:megaindex:';

    /**
     * Конструктор модели, получает необходимые настройки для работы с Мегаиндексом.
     * @param AdminComponent $component компонент
     * @throws InvalidArgumentException в случае если не заполнены обязательные параметры конфигурации
     */
    public function __construct(AdminComponent $component)
    {
        $this->component = $component;

        $this->login = $this->component->getSetting(MegaindexModel::MEGAINDEX_LOGIN);
        $this->password = $this->component->getSetting(MegaindexModel::MEGAINDEX_PASSWORD);
        $this->siteUrl = $this->component->getSetting(MegaindexModel::MEGAINDEX_SITE_URL);

        if (is_null($this->login)) {
            throw new InvalidArgumentException($this->component->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_LOGIN]
            ));
        }
        if (is_null($this->password)) {
            throw new InvalidArgumentException($this->component->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_PASSWORD]
            ));
        }
        if (is_null($this->siteUrl)) {
            throw new InvalidArgumentException($this->component->translate(
                "Option {option} is required",
                ['option' => MegaindexModel::MEGAINDEX_SITE_URL]
            ));
        }
    }

    /**
     * Универсальный метод, запрашивающий данные через API Мегаиндекса.
     * Возвращает массив данных
     * @param string $method имя метода, методы перечислены в {@link http://api.megaindex.ru/description/ документации}
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

        $result['data'] = \GuzzleHttp\get('http://api.megaindex.ru/', ['query' => $paramsMerged])
            ->json(['object' => false]);

        if (isset($result['data'][0])) {
            $result['labels'] = $this->getLabel($result['data'][0], $method);
        }

        return $result;
    }

    /**
     * Возвращает метки полей.
     * @param array $data данные
     * @param string $resource наименование ресурса
     * @return array список меток
     */
    protected function getLabel(array $data, $resource)
    {
        $labels = [];
        foreach($data as $key => $host) {
            if (!is_int($key)) {
                $labels[$key] = $this->component->translate($this->translationPrefix . $resource . ucfirst($key));
            }
            if (is_array($host)) {
                $labels = array_merge($labels, $this->getLabel($host, $resource));
            }
        }

        return $labels;
    }
}
