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

/**
 * API для работы с лицензией.
 */
class LicenseApi
{
    /**
     * Активация лиуцензии.
     * @param string $licenseKey лицензионный ключ
     * @param string $domain домен
     * @param IConfig $config конфиг
     * @return bool
     */
    public function activate($licenseKey, $domain, IConfig $config)
    {
        if ($this->checkLicenseKey($licenseKey, $domain)) {
            $license = $this->activateLicense($licenseKey, $domain);
            $config->set('domainKey', $license['domain-keycode']);
            $config->set('licenseType', $license['codename']);

            if (strstr($license['codename'], base64_decode('dHJpYWw='))) {
                $deactivation = new \DateTime();
                $deactivation->add(new \DateInterval('P30D'));
                $config->set('deactivation', base64_encode($deactivation->getTimestamp()));
            }

            return true;
        }

        return false;
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

        $params = array(
            'ip' => $_SERVER['SERVER_ADDR'],
            'domain' => $domain,
            'keycode' => $licenseKey
        );

        $result = \GuzzleHttp\get(
            base64_decode($source) . base64_encode(serialize($params)).'/'
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

        $params = array(
            'ip' => $_SERVER['SERVER_ADDR'],
            'domain' => $domain,
            'keycode' => $licenseKey
        );

        $result = \GuzzleHttp\get(
            base64_decode($source) . '?' . http_build_query($params)
        )->json();

        return $result['license'];
    }
}
 