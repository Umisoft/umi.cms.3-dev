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

use SimpleXMLElement;
use umi\hmvc\model\IModel;
use umi\i18n\ILocalizable;
use umi\i18n\TLocalizable;

/**
 * Модель, получающая данные от API Мегаиндекса.
 */
class YandexModel implements IModel, ILocalizable
{
    use TLocalizable;

    /**
     * Идентификатор хоста.
     */
    const YANDEX_HOST_ID = 'hostId';
    /**
     * Полученный oAuth token.
     */
    const YANDEX_OAUTH_TOKEN = 'oauthToken';

    /**
     * @var string $oauthToken
     */
    private $oauthToken;

    private $translationPrefix = 'component:yandex:';

    /**
     * Конструктор модели, требует токен для запросов к Яндексу.
     * @param string $oauthToken
     */
    public function __construct($oauthToken)
    {
        $this->oauthToken = $oauthToken;
    }

    /**
     * Универсальный метод, запрашивает и возвращает данные от API вебмастера
     * @param string $resource Путь REST-ресурса
     * @param array $params Дополнительные параметры запроса
     * @return array Результат запроса
     */
    protected function queryApi($resource, array $params = [])
    {
        $xml = \GuzzleHttp\get(
            'http://webmaster.yandex.ru/api/v2/' . $resource,
            ['query' => $params, 'headers' => ['Authorization' => 'OAuth ' . $this->oauthToken]]
        )->xml();
        return static::simpleXMLToArray($xml);
    }

    /**
     * Возвращает список {@link http://api.yandex.ru/webmaster/doc/dg/reference/hosts.xml доступных сайтов}.
     * @return array
     */
    public function getHosts()
    {
        $resource = 'hosts';
        $result['data'] = $this->queryApi($resource);

        $result['labels'] = [];
        if (isset($result['data']['host'][0])) {
            $result['labels'] = $this->getLabel($result['data']['host'][0], $resource);
        }

        return $result;
    }

    /**
     * Возвращает {@link http://api.yandex.ru/webmaster/doc/dg/reference/hosts-id.xml статистические данные}
     * по зарегистрированному сайту.
     *
     * @param int $siteId
     * @return array
     */
    public function getHostStats($siteId)
    {
        $resource = 'hostsStats';

        $result['data'] = $this->queryApi('hosts/' . $siteId . '/stats');

        $result['labels'] = $this->getLabel($result['data'], $resource);

        return $result;
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/hosts-indexed.xml проиндексированных страницах}.
     * @param int $hostId
     * @return array
     */
    public function getIndexedStats($hostId)
    {
        $resource = 'hostsIndexed';

        $result['data'] = $this->queryApi('hosts/' . $hostId . '/indexed');

        $result['labels'] = $this->getLabel($result['data'], $resource);

        return $result;
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/host-links.xml внешних ссылках на сайт}.
     * @param int $hostId
     * @return array
     */
    public function getLinksStats($hostId)
    {
        $resource = 'hostsLinks';

        $result['data'] = $this->queryApi('hosts/' . $hostId . '/links');

        $result['labels'] = $this->getLabel($result['data'], $resource);

        return $result;
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/host-tops.xml популярных запросах}.
     * @param int $hostId
     * @return array
     */
    public function getTopsStats($hostId)
    {
        $resource = 'hostsTops';

        $result['data'] = $this->queryApi('hosts/' . $hostId . '/tops');

        $result['labels'] = $this->getLabel($result['data'], $resource);

        return $result;
    }

    /**
     * @param SimpleXMLElement $xmlElement
     * @return array
     */
    public static function simpleXMLToArray(SimpleXMLElement $xmlElement)
    {
        $return = [];
        $elementText = trim((string) $xmlElement);

        if (!strlen($elementText)) {
            $elementText = null;
        };

        if (!is_null($elementText)) {
            $return = (array) $elementText;
        }

        $sameChildren = [];
        $first = true;
        foreach ($xmlElement->children() as $tagName => $child) {
            $childrenArray = static::simpleXMLToArray($child);
            if (isset($sameChildren[$tagName])) {
                if (is_array($sameChildren[$tagName])) {
                    if ($first) {
                        $temp = $sameChildren[$tagName];
                        unset($sameChildren[$tagName]);
                        $sameChildren[$tagName][] = $temp;
                        $first = false;
                    }
                    $sameChildren[$tagName][] = $childrenArray;
                } else {
                    $sameChildren[$tagName] = [$sameChildren[$tagName], $childrenArray];
                }
            } else {
                $sameChildren[$tagName] = $childrenArray;
            }
        }
        if ($sameChildren) {
            $return = array_merge($return, $sameChildren);
        }

        $attributes = [];
        foreach ($xmlElement->attributes() as $name => $attrValue) {
            $attributes[$name] = trim($attrValue);
        }
        if ($attributes) {
            $return = array_merge($return, $attributes);
        }

        return $return;
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
                $labels[$key] = $this->translate($this->translationPrefix . $resource . ucfirst($key));
            }
            if (is_array($host)) {
                $labels = array_merge($labels, $this->getLabel($host, $resource));
            }
        }

        return $labels;
    }
}
