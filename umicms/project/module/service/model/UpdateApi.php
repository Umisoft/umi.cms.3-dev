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

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use umi\http\Request;
use umicms\exception\RuntimeException;
use umicms\project\Environment;
use umicms\project\TProjectSettingsAware;

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
     * @var LicenseApi $licenseApi
     */
    protected $licenseApi;

    /**
     * Конструктор.
     * @param ServiceModule $service
     * @param Request $request
     */
    public function __construct(ServiceModule $service, Request $request)
    {
        $this->licenseApi = $service->license();
        $this->request = $request;
    }

    /**
     * Возвращает информацию о последней версии с сервера обновлений
     * @throws RuntimeException если не удалось получить информацию
     * @return array
     */
    public function getLatestVersionInfo()
    {
        $result = $this->queryServer(['type' => 'get-latest-version'])->xml();

        if (empty($result->version) || empty($result->date)) {
            throw new RuntimeException('Cannot detect latest version. Invalid update server response.');
        }

        return [
            'version' => (string) $result->version,
            'date' => (string) $result->date
        ];
    }

    /**
     * Скачивает пакет обновления и возвращает ссылку на него
     * @return string
     */
    public function downloadUpdate()
    {
        $response = $this->queryServer(['type' => 'get-update']);
        $updaterFile = Environment::$directoryRoot . '/update.phar.php';
        file_put_contents($updaterFile, $response->getBody());
        return Environment::$baseUrl . '/update.phar.php';
    }

    /**
     * Возвращает информацию о текущей версии
     */
    public function getCurrentVersionInfo()
    {
        return [
            'version' => CMS_VERSION,
            'date' => CMS_VERSION_DATE
        ];
    }

    /**
     * Запрашивает сервер обновлений
     * @param array $params параметры запроса
     * @throws RuntimeException
     * @return ResponseInterface
     */
    private function queryServer(array $params = [])
    {
        $params['licenseKey'] = $this->licenseApi->getDomainKey();
        $params['serverAddress'] = $this->request->server->get('SERVER_ADDR');
        $params['domain'] = $this->request->getHost();

        $query =  base64_decode(self::UPDATE_SERVER) . '?' . http_build_query($params);

        try {
            $response = \GuzzleHttp\get($query);
        } catch (RequestException $e) {
            throw new RuntimeException($e->getResponse()->xml()->error);
        }

        return $response;
    }

}
 