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
 * Сохранение настроек для подключения к БД.
 */
class SaveDbConfig implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * @var array $db настройки для подключения к БД
     */
    private $db = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
        $this->db = $param;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (
            empty($this->db['dbname']) ||
            empty($this->db['host']) ||
            empty($this->db['login']) ||
            empty($this->db['password'])
        ) {
            throw new RuntimeException(
                'Недостаточно данных для подключения БД'
            );
        }

        $config = $this->installer->getConfig();
        $config['db'] = $this->db;
        $this->installer->saveConfig($config);

        return true;
    }
}
 