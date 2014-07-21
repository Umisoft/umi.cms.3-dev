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
 * Настройка доступа к сайту.
 */
class SiteAccess implements ICommandInstall
{
    /**
     * @var Installer $installer
     */
    private $installer;

    /**
     * @var array $siteAccess
     */
    private $siteAccess;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
        $this->siteAccess = $param;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (
            empty($this->siteAccess['login']) ||
            empty($this->siteAccess['email']) ||
            empty($this->siteAccess['password']) ||
            empty($this->siteAccess['password2'])
        ) {
            throw new RuntimeException(
                'Не все обязательные поля заполнены.'
            );
        }

        if (filter_var($this->siteAccess['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Поле Email заполнено неверно.');
        }

        if ($this->siteAccess['password'] !== $this->siteAccess['password2']) {
            throw new RuntimeException('Пароли не совпадают.');
        }

        $config = $this->installer->getConfig();
        $config['siteAccess'] = $this->siteAccess;
        $this->installer->saveConfig($config);

        return true;
    }
}
 