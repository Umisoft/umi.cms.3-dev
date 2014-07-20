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
        if (!$this->installer->copyRemote('http://localhost/umicms.phar', CMS_CORE_PHAR)) {
            throw new RuntimeException(
                'Неудалось скачать core.'
            );
        }

        return true;
    }
}
 