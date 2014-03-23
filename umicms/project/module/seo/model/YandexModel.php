<?php
/**
 * UMI.Framework (http://umi-framework.ru/)
 *
 * @link      http://github.com/Umisoft/framework for the canonical source repository
 * @copyright Copyright (c) 2007-2013 Umisoft ltd. (http://umisoft.ru/)
 * @license   http://umi-framework.ru/license/bsd-3 BSD-3 License
 */
namespace umicms\project\module\seo\model;

use SimpleXMLElement;
use umi\hmvc\model\IModel;

/**
 * Модель, получающая данные от API Мегаиндекса.
 */
class YandexModel implements IModel
{
    /**
     * @var string $oauthToken
     */
    private $oauthToken;

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
     */
    public function getHosts()
    {
        return $this->queryApi('hosts');
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
        return $this->queryApi('hosts/' . $siteId . '/stats');
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/hosts-indexed.xml проиндексированных страницах}.
     * @param int $hostId
     * @return array
     */
    public function getIndexedStats($hostId)
    {
        return $this->queryApi('hosts/' . $hostId . '/indexed');
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/host-links.xml внешних ссылках на сайт}.
     * @param int $hostId
     * @return array
     */
    public function getLinksStats($hostId)
    {
        return $this->queryApi('hosts/' . $hostId . '/links');
    }

    /**
     * Возвращает информацию
     * о {@link http://api.yandex.ru/webmaster/doc/dg/reference/host-tops.xml популярных запросах}.
     * @param int $hostId
     * @return array
     */
    public function getTopsStats($hostId)
    {
        return $this->queryApi('hosts/' . $hostId . '/tops');
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
}
