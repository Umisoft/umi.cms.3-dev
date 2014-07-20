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
 * Скачивает проект.
 */
class DownloadProject implements ICommandInstall
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

        if (!isset($config['projectName']) || !in_array($config['projectName'], $this->installer->getTypeProject())) {
            throw new RuntimeException('Неудалось скачать проект.');
        }

        if (!$this->installer->copyRemote('http://localhost/demo-' . $config['projectName'] . '.phar', ROOT_DIR . '/demo-' . $config['projectName'] . '.phar')) {
            throw new RuntimeException(
                'Неудалось скачать project.'
            );
        }

        return true;
    }
}
 