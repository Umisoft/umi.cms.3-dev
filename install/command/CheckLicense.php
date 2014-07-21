<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\install\command;

use umicms\install\installer\Installer;
use umicms\install\exception\RuntimeException;

/**
 * Проверка лицензионного ключа.
 */
class CheckLicense implements ICommandInstall
{
    /**
     * @var string $licenseKey лицензионный ключ
     */
    private $licenseKey;

    /**
     * * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->licenseKey = $param;
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (is_null($this->licenseKey)) {
            throw new RuntimeException(
                'Не введён лицензионный ключ.'
            );
        }
        if (!$this->installer->checkLicenseKey($this->licenseKey)) {
            throw new RuntimeException(
                'Неверный лицензионный ключ.'
            );
        }

        try {
            $config = $this->installer->getConfig();
        } catch (\Exception $e) {
            $config = [];
        }
        $config['license'] = [];
        $config['license']['licenseKey'] = $this->licenseKey;
        $config['license']['serverAddress'] = $_SERVER['SERVER_ADDR'];
        $config['license']['domain'] = $this->installer->getHostDomain();
        $this->installer->saveConfig($config);

        return true;
    }
}
 