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

use umi\config\io\IConfigIOAware;
use umi\form\element\IFormElement;
use umi\http\Response;
use umicms\hmvc\component\admin\settings\BaseController;
use umicms\hmvc\component\admin\settings\SettingsComponent;
use umicms\hmvc\component\admin\TActionController;
use umicms\Utils;

/**
 * Контроллер действий над настройками
 */
class ActionController extends BaseController implements IConfigIOAware
{
    use TActionController;

    /**
     * Сохраняет форму редактирования конфигурации.
     * @return Response
     */
    protected function actionSave()
    {
        /**
         * @var SettingsComponent $component
         */
        $component = $this->getComponent();

        $config = $this->readConfig($component->getSettingsConfigAlias());
        $form = $this->getForm(self::SETTINGS_FORM_NAME);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        $form->setData($this->getAllPostVars());

        if ($form->isValid()) {

            /**
             * @var IFormElement $emailSenderInput
             */
            $emailSenderInput = $form->get('sender_address');
            $mailSenderInfo = Utils::parseEmailList($emailSenderInput->getValue());

            $config->del('mailerOptions.sender_address');

            if ($mailSenderInfo) {
                $i = 0;
                foreach ($mailSenderInfo as $key => $value) {
                    if (is_numeric($key)) {
                        $config->set('mailerOptions.sender_address.'. $i . '.email', $value);
                    } else {
                        $config->set('mailerOptions.sender_address.'. $i . '.email', $key);
                        $config->set('mailerOptions.sender_address.'. $i . '.name', $value);
                    }
                    $i++;
                }
            }

            $this->writeConfig($config);
        } else {
            $this->setResponseStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $form->getView();
    }

}
 