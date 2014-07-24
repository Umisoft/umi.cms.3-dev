<?php
/**
 * This file is part of UMI.CMS.
 *
 * @link http://umi-cms.ru
 * @copyright Copyright (c) 2007-2014 Umisoft ltd. (http://umisoft.ru)
 * @license For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umicms\project\site\settings\license\controller;

use umi\form\element\Text;
use umi\http\Response;
use umicms\hmvc\component\admin\settings\ActionController as BaseActionController;
use umicms\hmvc\component\admin\settings\SettingsComponent;
use umicms\project\module\service\model\ServiceModule;

/**
 * Контроллер действий над настройками.
 */
class ActionController extends BaseActionController
{

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
        $form = $this->getForm(self::SETTINGS_FORM_NAME, $config);
        $form->setAction($this->getUrl('action', ['action' => 'save']));

        $form->setData($this->getAllPostVars());

        if ($form->isValid()) {
            $licenseKey = $form->get('licenseKey');
            if ($licenseKey instanceof Text) {
                $licenseKey = $licenseKey->getValue();
            }

            $domain = $form->get('defaultDomain');
            if ($domain instanceof Text) {
                $domain = $domain->getValue();
            }

            /** @var ServiceModule $serviceModule */
            $serviceModule = $this->getModuleByClass(ServiceModule::className());
            $serviceModule->license()->activate($licenseKey, $domain);
        } else {
            $this->setResponseStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $form->getView();
    }
}
 