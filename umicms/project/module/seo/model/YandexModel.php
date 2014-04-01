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
     * @param string $resource
     * @param array $params
     * @return SimpleXMLElement
     */
    protected function queryApi($resource, array $params = [])
    {
        $paramsMerged = array_merge(
            [
                'access_token' => $this->oauthToken,
            ],
            $params
        );
        $xml =  \GuzzleHttp\get('http://webmaster.yandex.ru/api/v2'.$resource, ['query' => $paramsMerged])->xml();
        return $this->xmlToJson($xml);
    }

    public function getSiteStats($siteId){
        return $this->queryApi('hosts/'.$siteId.'/stats');
    }

    private function xmlToJson($xml)
    {
        foreach ($xml as $element) {

        }

    }
}
