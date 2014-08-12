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
 * Скачивание ядра системы.
 */
class DownloadCore implements ICommandInstall
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
                'Не удалось скачать core. Ошибка лицензии.'
            );
        }
        $config['license']['type'] = 'get-core';
        $path = $this->installer->getUpdateLink() . '?' . http_build_query($config['license']);

        if (!$this->installer->copyRemote($path, CMS_CORE_PHAR)) {
            throw new RuntimeException(
                'Не удалось скачать core.'
            );
        }

        return true;
    }
}
 