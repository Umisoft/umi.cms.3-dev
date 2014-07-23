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

use umicms\install\exception\RuntimeException;
use umicms\install\installer\Installer;

/**
 * Проверка подключения к БД.
 */
class CheckDb implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
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
        if (
            !isset($config['db']['dbname']) ||
            !isset($config['db']['host']) ||
            !isset($config['db']['login']) ||
            !isset($config['db']['password'])
        ) {
            throw new RuntimeException(
                'Неудалось установить соединение с БД.'
            );
        }

        if (!$this->installer->checkConnectionDb(
            $config['db']['dbname'],
            $config['db']['host'],
            $config['db']['login'],
            $config['db']['password']
        )) {
            throw new RuntimeException(
                'Неудалось установить соединение с БД.'
            );
        }

        return true;
    }
}
 