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
 * Получение триальной лицензии.
 */
class TrialLicense implements ICommandInstall
{
    /**
     * @var Installer $installer инсталятор
     */
    private $installer;

    /**
     * @var array $trialInfo информация о владельце триального ключа
     */
    private $trialInfo;

    /**
     * {@inheritdoc}
     */
    public function __construct(Installer $installer, $param = null)
    {
        $this->installer = $installer;
        $this->trialInfo = $param;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (empty($this->trialInfo['email'])) {
            throw new RuntimeException('Поле Email обязательно для заполнения');
        }

        if (filter_var($this->trialInfo['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Поле Email заполнено неверно.');
        }

        return $this->installer->getTrialLicense($this->trialInfo['email'], $this->trialInfo['fname'], $this->trialInfo['lname']);
    }
}
 