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

use install\exception\RuntimeException;

/**
 * Регистр команд.
 */
class InstallCommandRegistry
{
    /**
     * @var array $registry список зарегистрированных команд
     */
    private $registry = [];

    /**
     * Регистрация команды.
     * @param ICommandInstall $command
     * @param string $commandName имя команды
     */
    public function add(ICommandInstall $command, $commandName)
    {
        $this->registry[$commandName] = $command;
    }

    /**
     * Возвращает обработчик команды.
     * @param string $commandName имя команды
     * @throws RuntimeException
     * @return ICommandInstall
     */
    public function get($commandName)
    {
        if (!isset($this->registry[$commandName])) {
            throw new RuntimeException('Команда "' . $commandName . '" не найдена.');
        }
        return $this->registry[$commandName];
    }
}
 