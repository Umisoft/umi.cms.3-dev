<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\settings\license\controller;

use umi\config\entity\IConfig;
use umi\form\element\Text;
use umi\form\IForm;
use umicms\hmvc\component\admin\settings\ActionController as BaseActionController;

/**
 * Контроллер действий над настройками.
 */
class ActionController extends BaseActionController
{
    /**
     * {@inheritdoc}
     */
    protected function processForm(IForm $form, IConfig $config)
    {
        $licenseKey = $form->get('licenseKey');
        if ($licenseKey instanceof Text) {
            $licenseKey = $licenseKey->getValue();
        }

        $domain = $form->get('defaultDomain');
        if ($domain instanceof Text) {
            $domain = $domain->getValue();
        }

        if (
            (!empty($licenseKey) && is_string($licenseKey)) &&
            (!empty($domain) && is_string($domain))
        ) {
            if ($this->checkLicenseKey($licenseKey, $domain)) {
                $license = $this->activateLicense($licenseKey, $domain);
                $config->set('domainKey', $license['domain-keycode']);
                $config->set('licenseType', $license['codename']);

                if (strstr($license['codename'], base64_decode('dHJpYWw='))) {
                    $deactivation = new \DateTime();
                    $deactivation->add(new \DateInterval('P30D'));
                    $config->set('deactivation', base64_encode($deactivation->getTimestamp()));
                }
            }
        }

        $config->set('licenseKey', '');
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
 