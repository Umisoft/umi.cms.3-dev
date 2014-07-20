<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace install\command;

use install\installer\Installer;
use install\exception\RuntimeException;

/**
 * Скачивает пакет с окружением.
 */
class DownloadEnvironment implements ICommandInstall
{
    /**
     * @var Installer $installer
     */
    private $installer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $config = $this->installer->getConfig();
        if (!isset($config['license'])) {
            throw new RuntimeException(
                'Неудалось скачать core. Ошибка лицензии.'
            );
        }
        $config['license']['type'] = 'get-environment';
        $path = $this->installer->getUpdateLink() . '?' . http_build_query($config['license']);

        if (!isset($config['projectName']) || !in_array($config['projectName'], $this->installer->getTypeProject())) {
            throw new RuntimeException('Неудалось скачать окружение.');
        }

        if (!$this->installer->copyRemote($path, ENVIRONMENT_PHAR)) {
            throw new RuntimeException(
                'Неудалось скачать окружение.'
            );
        }

        return true;
    }
}
 