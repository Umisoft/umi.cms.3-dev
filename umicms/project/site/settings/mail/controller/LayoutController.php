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
use umi\messages\toolbox\MessagesTools;
use umicms\hmvc\component\admin\layout\button\behaviour\Behaviour;
use umicms\hmvc\component\admin\layout\button\Button;
use umicms\hmvc\component\admin\settings\BaseController;

/**
 * Контроллер вывода настроек компонента, управляющего настройками
 */
class LayoutController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $saveBehaviour = new Behaviour('save');
        $saveButton = new Button($this->getComponent()->translate('button:save'), $saveBehaviour);
        $saveButton->attributes['hasIcon'] = false;
        $saveButton->attributes['class'] = 'button';

        $config = $this->readConfig($this->getComponent()->getSettingsConfigAlias());

        $sendersInfo = $config->get(MessagesTools::NAME . '.mailerOptions.sender_address');
        $sendersInfo = ($sendersInfo instanceof IConfig) ? $sendersInfo->toArray() : [];
        $recipientsInfo = $config->get(MessagesTools::NAME . '.mailerOptions.delivery_address');
        $recipientsInfo = ($recipientsInfo instanceof IConfig) ? $recipientsInfo->toArray() : [];

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

        $to = [];
        foreach ($recipientsInfo as $recipientInfo) {
            if (isset($recipientInfo['email'])) {
                if (isset($recipientInfo['name'])) {
                    $to[] = $recipientInfo['name'] . ' <' . $recipientInfo['email'] . '>';
                } else {
                    $to[] = $recipientInfo['email'];
                }
            }
        }

        $data = [
            'sender_address' => $from ? implode(', ', $from) : null,
            'delivery_address' => $to ? implode(', ', $to) : null
        ];

        $form = $this->getForm(self::SETTINGS_FORM_NAME);
        $form->setData($data);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        return $this->createViewResponse(
            'simpleForm',
            [
                'simpleForm' => [
                    'submitToolbar' => [
                        $saveButton->build()
                    ],
                    'meta' => $form->getView()
                ]
            ]
        );
    }
}
 