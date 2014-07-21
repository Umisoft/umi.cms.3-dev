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
use umicms\module\toolbox\ModuleTools;
use umicms\project\Bootstrap;

/**
 * Устанавливает систему.
 */
class InstallProject implements ICommandInstall
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
        require(CMS_CORE_PHP);

        $bootstrap = new Bootstrap();
        $bootstrap->dispatchProject();

        $toolkit = $bootstrap->getToolkit();
        /**
         * @var ModuleTools $moduleTools
         */
        $moduleTools = $toolkit->getToolbox(ModuleTools::NAME);
        foreach ($moduleTools->getModules() as $module) {
            $module->getModelCollection()->syncAllSchemes();
        }

        return true;
    }
}
