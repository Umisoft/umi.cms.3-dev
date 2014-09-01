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

use umi\config\entity\IConfig;
use umi\config\io\IConfigIOAware;
use umi\config\io\TConfigIOAware;
use umi\http\Request;
use umicms\exception\InvalidLicenseException;

/**
 * API для работы с лицензией.
 */
class LicenseApi implements IConfigIOAware
{
    use TConfigIOAware;

    /**
     * @var Request $request
     */
    protected $request;
    /**
     * @var IConfig $config
     */
    private $config;

    /**
     * Конструктор.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Возвращает доменный ключ.
     * @return string
     */
    public final function getDomainKey()
    {
        return $this->getConfig()->get('domainKey');
    }

    /**
     * Активация лицензии.
     * @param string $licenseKey лицензионный ключ
     * @param string $domain домен
     * @param IConfig $config
     * @throws InvalidLicenseException
     */
    public final function activate($licenseKey, $domain, IConfig $config = null)
    {
        if (!$config) {
            $config = $this->getConfig();
        }

        if (!$this->checkLicenseKey($licenseKey, $domain)) {
            throw new InvalidLicenseException('Cannot activate license. Invalid license key.');
        }

        $license = $this->activateLicense($licenseKey, $domain);
        $config->set('defaultDomain', $domain);
        $config->set('domainKey', $license['domain-keycode']);
        $config->set('licenseType', $license['codename']);

        if (strstr($license['codename'], base64_decode('dHJpYWw='))) {
            $deactivation = new \DateTime();
            $deactivation->add(new \DateInterval('P30D'));
            $config->set('deactivation', base64_encode($deactivation->getTimestamp()));
        }

        $this->writeConfig($config);
    }

    /**
     * Возвращает конфигурацию
     * @return IConfig
     */
    private function getConfig()
    {
        if (!$this->config) {
            $this->config = $this->readConfig('~/project/configuration/project.config.php');
        }
        return $this->config;
    }

    /**
     * Проверка лицензионного ключа.
     * @param string $licenseKey лицензионный ключ
     * @param string $domain домен
     * @return bool
     */
    private function checkLicenseKey($licenseKey, $domain)
    {
        $source = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91ZGF0YTovL2N1c3RvbS9wcmltYXJ5Q2hlY2tDb2RlLw==';

        $params = [
            'ip' => $this->request->server->get('SERVER_ADDR'),
            'domain' => $domain,
            'keycode' => $licenseKey
        ];

        $result = \GuzzleHttp\get(
            base64_decode($source) . base64_encode(serialize($params)) . '/'
        )->xml();

        if (isset($result->result)) {
            return true;
        }

        return false;
    }

    /**
     * Активация лицензии.
     * @param string $licenseKey лицензионный ключ
     * @param string $domain домен
     * @return string
     */
    private function activateLicense($licenseKey, $domain)
    {
        $source = 'aHR0cDovL3VwZGF0ZXMudW1pLWNtcy5ydS91cGRhdGVzcnYvYWN0aXZhdGVVbWlDbXNMaWNlbnNlLw==';

        $params = [
            'ip' => $this->request->server->get('SERVER_ADDR'),
            'domain' => $domain,
            'keycode' => $licenseKey
        ];

        $result = \GuzzleHttp\get(
            base64_decode($source) . '?' . http_build_query($params)
        )->json();

        return $result['license'];
    }
}
 