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

        if (!isset($config['license'])) {
            throw new RuntimeException(
                'Не удалось скачать core. Ошибка лицензии.'
            );
        }

        if (!isset($config['projectName']) || !in_array($config['projectName'], $this->installer->getTypeProject())) {
            throw new RuntimeException('Не удалось скачать проект.');
        }

        $config['license']['type'] = 'get-project';
        $config['license']['project'] = $config['projectName'];
        $path = $this->installer->getUpdateLink() . '?' . http_build_query($config['license']);

        if (!$this->installer->copyRemote($path, INSTALL_ROOT_DIR . '/' . $config['projectName'] . '.phar')) {
            throw new RuntimeException(
                'Не удалось скачать project.'
            );
        }

        return true;
    }
}
 