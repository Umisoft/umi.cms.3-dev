<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\console;

use Symfony\Component\Console\Application;

/**
 * Консольное приложение UMI.CMS
 */
class ConsoleApplication extends Application
{
    public function getHelp()
    {
        $logo = <<<EOF
  _   _   __  __   ___        ____   __  __   ____        _____
 | | | | |  \/  | |_ _|      / ___| |  \/  | / ___|      |___ /
 | | | | | |\/| |  | |      | |     | |\/| | \___ \        |_ \
 | |_| | | |  | |  | |   _  | |___  | |  | |  ___) |      ___) |
  \___/  |_|  |_| |___| (_)  \____| |_|  |_| |____/      |____/


EOF;

        return '<fg=blue;options=bold>' . $logo . '</fg=blue;options=bold>' . parent::getHelp();
    }
}
 