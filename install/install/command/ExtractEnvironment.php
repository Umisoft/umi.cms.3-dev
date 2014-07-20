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
use Phar;
use install\exception\RuntimeException;

/**
 * Распаковывает пакет окружения.
 */
class ExtractEnvironment implements ICommandInstall
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
        if (!file_exists(ENVIRONMENT_PHAR)) {
            throw new RuntimeException('Отсутствует пакет с окружением.');
        }

        $phar = new Phar(ENVIRONMENT_PHAR);
        if (!$phar->extractTo(ROOT_DIR, null, true)) {
            throw new RuntimeException(
                'Неудалось распаковать окружение.'
            );
        }

        return true;
    }
}
 