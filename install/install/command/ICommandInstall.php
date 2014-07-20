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

/**
 * Интекфейс команды инсталятора.
 */
interface ICommandInstall
{
    /**
     * Конструктор.
     * @param Installer $installer инсталятор
     * @param null|string $param параметр
     */
    public function __construct(Installer $installer, $param = null);

    /**
     * Команда.
     * @return mixed
     */
    public function execute();
}
 