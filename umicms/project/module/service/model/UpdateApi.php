<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\module\service\model;

use GuzzleHttp\Message\ResponseInterface;
use umi\http\Request;
use umicms\project\site\config\TSiteSettingsAware;

/**
 * API для работы обновления системы.
 */
class UpdateApi
{

    /**
     * @var string $updateLink сервер обновлений
     */
    const UPDATE_SERVER = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91cGRhdGVzZXJ2ZXIzLw';

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * Конструктор.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Возвращает информацию о последней версии с сервера обновлений
     */
    public function getLatestVersionInfo()
    {
        $result = $this->queryServer(['type' => 'get-latest-version']);
var_dump($result);

    }

    /**
     * Возвращает информацию о текущей версии
     */
    public function getCurrentVersionInfo()
    {
        return [CMS_VERSION, CMS_VERSION_DATE];
    }

    /**
     * Запрашивает сервер обновлений
     * @param array $params параметры запроса
     * @return ResponseInterface
     */
    private function queryServer(array $params = [])
    {
        $params['domainKey'] = $this->getSiteSettings()->get('domainKey');
        $params['serverAddress'] = $this->request->get('SERVER_ADDR');
        $params['domain'] = $this->request->getHost();

        $query =  base64_decode(self::UPDATE_SERVER) . '?' . http_build_query($params);

        return \GuzzleHttp\get($query);
    }

}
 