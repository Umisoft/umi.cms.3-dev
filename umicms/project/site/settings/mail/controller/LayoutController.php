<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\settings\mail\controller;

use umi\config\entity\IConfig;
use umicms\hmvc\component\admin\settings\LayoutController as SettingsLayoutController;

/**
 * Контроллер вывода настроек компонента, управляющего настройками
 */
class LayoutController extends SettingsLayoutController
{
    /**
     * {@inheritdoc}
     */
    protected function getConfigForm()
    {
        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());

        $sendersInfo = $config->get('mailerOptions.sender_address');
        $sendersInfo = ($sendersInfo instanceof IConfig) ? $sendersInfo->toArray() : [];

        $from = [];
        foreach ($sendersInfo as $senderInfo) {
            if (isset($senderInfo['email'])) {
                if (isset($senderInfo['name'])) {
                    $from[] = $senderInfo['name'] . ' <' . $senderInfo['email'] . '>';
                } else {
                    $from[] = $senderInfo['email'];
                }
            }
        }

        $data = [
            'sender_address' => $from ? implode(', ', $from) : null
        ];

        $form = $this->getForm(self::SETTINGS_FORM_NAME);
        $form->setData($data);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        return $form;
    }
}
 