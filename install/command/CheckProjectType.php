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
 * Проверка типа проекта.
 */
class CheckProjectType implements ICommandInstall
{
    /**
     * @var string $projectType тип проекта
     */
    private $projectType;

    /**
     * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->projectType = $param;
        $this->installer = $installer;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (is_null($this->projectType)) {
            throw new RuntimeException(
                'Не выбран тип проекта.'
            );
        }
        if (!in_array($this->projectType, $this->installer->getTypeProject())) {
            throw new RuntimeException(
                'Выбран неизвестный тип проекта.'
            );
        }
        try {
            $config = $this->installer->getConfig();
        } catch (\Exception $e) {
            $config = [];
        }
        $config['projectName'] = $this->projectType;
        $this->installer->saveConfig($config);

        return true;
    }
}
 